<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// التأكد من أن المستخدم صاحب سكن
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$notification_id = $input['notification_id'] ?? 0;

if (!$notification_id) {
    echo json_encode(['success' => false, 'message' => 'المعرف مطلوب']);
    exit();
}

try {
    // 1. تحديث جدول الرسائل - العمود الصحيح هو message_id
    $stmt1 = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE message_id = ?");
    $stmt1->execute([$notification_id]);

    // 2. تحديث جدول التنبيهات - العمود الصحيح هو notification_id
    $stmt2 = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
    $stmt2->execute([$notification_id]);

    echo json_encode([
        'success' => true,
        'message' => 'تم التحديث بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ قاعدة بيانات: ' . $e->getMessage()
    ]);
}
?>