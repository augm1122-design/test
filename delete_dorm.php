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
    
    if (empty($input['dorm_id'])) {
        echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
        exit();
    }
    
    $dorm_id = $input['dorm_id'];
    
    // Get dorm name before deleting
    $stmt = $pdo->prepare("SELECT name FROM dorms WHERE dorm_id = ?");
    $stmt->execute([$dorm_id]);
    $dorm = $stmt->fetch();
    
    if (!$dorm) {
        echo json_encode(['success' => false, 'message' => 'السكن غير موجود']);
        exit();
    }
    
    // Check if there are active bookings
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM booking WHERE dorm_id = ? AND status IN ('Pending', 'Confirmed')");
    $stmt->execute([$dorm_id]);
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'لا يمكن حذف السكن لوجود حجوزات نشطة. يرجى إلغاء الحجوزات أولاً.'
        ]);
        exit();
    }
    
    // Delete dorm
    $stmt = $pdo->prepare("DELETE FROM dorms WHERE dorm_id = ?");
    $stmt->execute([$dorm_id]);
    
    // Log activity
    $log_sql = "INSERT INTO activity_log (user_id, action, table_name, record_id, details, ip_address, created_at) 
                VALUES (:user_id, 'DELETE', 'dorms', :record_id, :details, :ip, NOW())";
    
    $log_stmt = $pdo->prepare($log_sql);
    $log_stmt->execute([
        ':user_id' => $_SESSION['student_id'],
        ':record_id' => $dorm_id,
        ':details' => 'تم حذف السكن: ' . $dorm['name'],
        ':ip' => $_SERVER['REMOTE_ADDR']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حذف السكن بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

