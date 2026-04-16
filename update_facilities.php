<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is owner
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$dorm_id = $input['dorm_id'] ?? 0;

if (!$dorm_id) {
    echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
    exit();
}

try {
    // Check if dorm_facilities table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'dorm_facilities'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (isset($input['updates'])) {
        // Update multiple facilities
        $updates = $input['updates'];
        
        if ($tableExists) {
            // Update in dorm_facilities table
            $setClauses = [];
            $values = [];
            
            foreach ($updates as $key => $value) {
                $setClauses[] = "$key = ?";
                $values[] = $value;
            }
            
            $values[] = $dorm_id;
            $sql = "UPDATE dorm_facilities SET " . implode(', ', $setClauses) . " WHERE dorm_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        } else {
            // Update in dorms table
            $setClauses = [];
            $values = [];
            
            foreach ($updates as $key => $value) {
                $setClauses[] = "$key = ?";
                $values[] = $value;
            }
            
            $values[] = $dorm_id;
            $sql = "UPDATE dorms SET " . implode(', ', $setClauses) . " WHERE dorm_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        }
        
    } else if (isset($input['facility']) && isset($input['value'])) {
        // Update single facility
        $facility = $input['facility'];
        $value = $input['value'];
        
        if ($tableExists) {
            $stmt = $pdo->prepare("UPDATE dorm_facilities SET $facility = ? WHERE dorm_id = ?");
            $stmt->execute([$value, $dorm_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE dorms SET $facility = ? WHERE dorm_id = ?");
            $stmt->execute([$value, $dorm_id]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ المرافق بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

