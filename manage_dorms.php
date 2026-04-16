<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح - يرجى تسجيل الدخول كمدير']);
    exit();
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_all':
            // Get all dorms with all columns
            $stmt = $pdo->query("SELECT * FROM dorms ORDER BY dorm_id ASC");
            $dorms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $dorms
            ]);
            break;
            
        case 'get_columns':
            // Get table structure
            $stmt = $pdo->query("DESCRIBE dorms");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'columns' => $columns
            ]);
            break;
            
        case 'update':
            // Update dorm
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['dorm_id'])) {
                echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
                exit();
            }
            
            $dorm_id = $input['dorm_id'];
            unset($input['dorm_id']);
            
            // Build UPDATE query dynamically
            $set_parts = [];
            $params = [];
            
            foreach ($input as $key => $value) {
                if ($key !== 'dorm_id') {
                    $set_parts[] = "`$key` = ?";
                    $params[] = $value;
                }
            }
            
            $params[] = $dorm_id;
            
            $sql = "UPDATE dorms SET " . implode(', ', $set_parts) . " WHERE dorm_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode([
                'success' => true,
                'message' => 'تم تحديث السكن بنجاح'
            ]);
            break;
            
        case 'add':
            // Add new dorm
            $input = json_decode(file_get_contents('php://input'), true);

            // Remove dorm_id if exists (auto increment)
            unset($input['dorm_id']);

            // Auto-create owner account if contact_email is provided
            $owner_id = null;
            if (!empty($input['contact_email'])) {
                $contact_email = $input['contact_email'];

                // Check if owner account already exists with this email
                $stmt = $pdo->prepare("SELECT users_id FROM login WHERE email = ?");
                $stmt->execute([$contact_email]);
                $existing_owner = $stmt->fetch();

                if ($existing_owner) {
                    // Owner account exists, use it
                    $owner_id = $existing_owner['users_id'];
                } else {
                    // Create new owner account
                    $default_password = 'owner123';
                    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO login (email, password, level) VALUES (?, ?, 3)");
                    $stmt->execute([$contact_email, $hashed_password]);
                    $owner_id = $pdo->lastInsertId();
                }

                // Set owner_id in dorm data
                $input['owner_id'] = $owner_id;
            } else {
                // Handle owner_id - set to NULL if empty or invalid
                if (isset($input['owner_id'])) {
                    if (empty($input['owner_id']) || $input['owner_id'] === '' || $input['owner_id'] === 'NULL') {
                        $input['owner_id'] = null;
                    } else {
                        // Verify owner exists
                        $stmt = $pdo->prepare("SELECT users_id FROM login WHERE users_id = ? AND level = 3");
                        $stmt->execute([$input['owner_id']]);
                        if (!$stmt->fetch()) {
                            $input['owner_id'] = null; // Set to NULL if owner doesn't exist
                        }
                    }
                }
            }

            // Build INSERT query dynamically
            $columns = array_keys($input);
            $placeholders = array_fill(0, count($columns), '?');

            $sql = "INSERT INTO dorms (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($input));

            $new_dorm_id = $pdo->lastInsertId();

            $message = 'تم إضافة السكن بنجاح';
            if ($owner_id && !$existing_owner) {
                $message .= ' وتم إنشاء حساب المالك تلقائياً (كلمة المرور: owner123)';
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'dorm_id' => $new_dorm_id,
                'owner_id' => $owner_id
            ]);
            break;
            
        case 'delete':
            // Delete dorm
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['dorm_id'])) {
                echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
                exit();
            }
            
            $stmt = $pdo->prepare("DELETE FROM dorms WHERE dorm_id = ?");
            $stmt->execute([$input['dorm_id']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'تم حذف السكن بنجاح'
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'إجراء غير صالح']);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

