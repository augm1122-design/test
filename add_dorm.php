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
    if (empty($input['name']) || empty($input['location']) || empty($input['gender'])) {
        echo json_encode(['success' => false, 'message' => 'الرجاء ملء جميع الحقول المطلوبة']);
        exit();
    }
    
    // Insert dorm
    $sql = "INSERT INTO dorms (name, location, gender, description, price_range, room_type, owner_id, status, created_at) 
            VALUES (:name, :location, :gender, :description, :price_range, :room_type, :owner_id, 'active', NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $input['name'],
        ':location' => $input['location'],
        ':gender' => $input['gender'],
        ':description' => $input['description'] ?? '',
        ':price_range' => $input['price_range'] ?? '',
        ':room_type' => $input['room_type'] ?? '',
        ':owner_id' => $input['owner_id'] ?? null
    ]);
    
    $dorm_id = $pdo->lastInsertId();
    
    // Log activity
    $log_sql = "INSERT INTO activity_log (user_id, action, table_name, record_id, details, ip_address, created_at) 
                VALUES (:user_id, 'INSERT', 'dorms', :record_id, :details, :ip, NOW())";
    
    $log_stmt = $pdo->prepare($log_sql);
    $log_stmt->execute([
        ':user_id' => $_SESSION['student_id'],
        ':record_id' => $dorm_id,
        ':details' => 'تم إضافة سكن جديد: ' . $input['name'],
        ':ip' => $_SERVER['REMOTE_ADDR']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إضافة السكن بنجاح',
        'dorm_id' => $dorm_id
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

