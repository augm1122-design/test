<?php
require_once '../conn.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['subject']) || !isset($input['message'])) {
        echo json_encode(['success' => false, 'message' => 'جميع الحقول مطلوبة']);
        exit();
    }
    
    $name = $input['name'];
    $email = $input['email'];
    $subject = $input['subject'];
    $message = $input['message'];
    
    // Send message to admin (you can change this to admin email)
    $admin_email = 'admin@mu-dorms.com'; // Change this to actual admin email
    
    // Insert message into messages table for admin
    $stmt = $pdo->prepare("
        INSERT INTO messages (sender_email, recipient_email, subject, message_body, sent_at, is_read)
        VALUES (?, ?, ?, ?, NOW(), 0)
    ");
    
    $message_body = "من: $name\nالبريد: $email\n\n$message";
    $stmt->execute([$email, $admin_email, $subject, $message_body]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إرسال رسالتك بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

