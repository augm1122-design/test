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
$comment_id = $input['comment_id'] ?? 0;
$reply = $input['reply'] ?? '';

if (!$comment_id || !$reply) {
    echo json_encode(['success' => false, 'message' => 'معرف التعليق والرد مطلوبان']);
    exit();
}

try {
    // Update comment with owner reply
    $stmt = $pdo->prepare("UPDATE comments SET owner_reply = ?, reply_date = NOW() WHERE id = ?");
    $stmt->execute([$reply, $comment_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'تم إرسال الرد بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'التعليق غير موجود'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

