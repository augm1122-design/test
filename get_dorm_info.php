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

$dorm_id = $_GET['dorm_id'] ?? 0;

if (!$dorm_id) {
    echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
    exit();
}

try {
    // Get dorm information
    $stmt = $pdo->prepare("SELECT * FROM dorms WHERE dorm_id = ?");
    $stmt->execute([$dorm_id]);
    $dorm = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dorm) {
        echo json_encode(['success' => false, 'message' => 'السكن غير موجود']);
        exit();
    }
    
    echo json_encode([
        'success' => true,
        'dorm' => $dorm
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

