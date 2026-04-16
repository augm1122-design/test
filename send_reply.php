<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// 1. التحقق من صلاحية المالك
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$recipient_email = $input['recipient_email'] ?? '';
$subject = $input['subject'] ?? '';
$message_content = $input['message'] ?? $input['message_body'] ?? '';

if (!$recipient_email || !$subject || !$message_content) {
    echo json_encode(['success' => false, 'message' => 'جميع الحقول مطلوبة']);
    exit();
}

try {
    // جلب بيانات المالك (المرسل) من الجلسة
    $owner_email = $_SESSION['user_email'];
    $owner_id = $_SESSION['users_id'] ?? $_SESSION['user_id'];

    // 2. جلب ID الطالب (المستلم) بناءً على إيميله لإرسال الإشعار
    $stmtUser = $pdo->prepare("SELECT users_id FROM login WHERE email = ?");
    $stmtUser->execute([$recipient_email]);
    $recipient = $stmtUser->fetch();
    
    if (!$recipient) {
        echo json_encode(['success' => false, 'message' => 'المستلم غير موجود في النظام']);
        exit();
    }
    $recipient_id = $recipient['users_id'];

    $pdo->beginTransaction();

    // 3. إدخال الرسالة في جدول messages
    // تم استبدال sender_id بـ sender_email و message بـ message_body حسب جدولك
    $sql_msg = "INSERT INTO messages (sender_email, recipient_email, recipient_id, subject, message_body, sent_at, is_read)
                VALUES (?, ?, ?, ?, ?, NOW(), 0)";
    $stmt1 = $pdo->prepare($sql_msg);
    $stmt1->execute([$owner_email, $recipient_email, $recipient_id, $subject, $message_content]);

    // 4. إضافة إشعار للطالب ليظهر عنده الجرس
    $sql_notif = "INSERT INTO notifications (user_id, title, message, type, is_read, created_at)
                  VALUES (?, ?, ?, 'message', 0, NOW())";
    $stmt2 = $pdo->prepare($sql_notif);
    $notif_title = "رسالة من صاحب السكن";
    $notif_body = "لديك رد جديد بخصوص: " . $subject;
    $stmt2->execute([$recipient_id, $notif_title, $notif_body]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'تم إرسال الرد بنجاح'
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>