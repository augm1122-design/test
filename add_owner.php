<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
        echo json_encode(['success' => false, 'message' => 'الرجاء ملء جميع الحقول المطلوبة']);
        exit();
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT users_id FROM login WHERE email = ?");
    $stmt->execute([$input['email']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني مستخدم بالفعل']);
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($input['password'], PASSWORD_DEFAULT);
    
    // Insert owner
    $sql = "INSERT INTO login (name, email, password, level, gender, phone, address, is_active, created_at) 
            VALUES (:name, :email, :password, 3, :gender, :phone, :address, 1, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $input['name'],
        ':email' => $input['email'],
        ':password' => $hashed_password,
        ':gender' => $input['gender'] ?? 'male',
        ':phone' => $input['phone'] ?? '',
        ':address' => $input['address'] ?? ''
    ]);
    
    $owner_id = $pdo->lastInsertId();
    
    // Log activity
    $log_sql = "INSERT INTO activity_log (user_id, action, table_name, record_id, details, ip_address, created_at) 
                VALUES (:user_id, 'INSERT', 'login', :record_id, :details, :ip, NOW())";
    
    $log_stmt = $pdo->prepare($log_sql);
    $log_stmt->execute([
        ':user_id' => $_SESSION['student_id'],
        ':record_id' => $owner_id,
        ':details' => 'تم إضافة مالك جديد: ' . $input['name'],
        ':ip' => $_SERVER['REMOTE_ADDR']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إضافة المالك بنجاح',
        'owner_id' => $owner_id
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

