<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is owner
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$comment_id = $input['comment_id'] ?? 0;

if (!$comment_id) {
    echo json_encode(['success' => false, 'message' => 'معرف التعليق مطلوب']);
    exit();
}

try {
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    
    // Verify the comment belongs to owner's dorm
    $stmt = $pdo->prepare("SELECT c.id FROM comments c 
                           JOIN dorms d ON c.dorm_id = d.dorm_id 
                           WHERE c.id = ? AND d.owner_id = ?");
    $stmt->execute([$comment_id, $owner_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح لحذف هذا التعليق']);
        exit();
    }
    
    // Delete comment
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حذف التعليق بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

