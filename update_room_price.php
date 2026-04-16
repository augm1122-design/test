<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is owner
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $room_id = $input['room_id'] ?? 0;
    $price = $input['price'] ?? 0;
    
    if (empty($room_id) || $price < 0) {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
        exit();
    }
    
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    
    // Verify owner owns this room's dorm
    $stmt = $pdo->prepare("
        SELECT dr.room_id 
        FROM dorm_rooms dr
        JOIN dorms d ON dr.dorm_id = d.dorm_id
        WHERE dr.room_id = ? AND d.owner_id = ?
    ");
    $stmt->execute([$room_id, $owner_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح للوصول لهذه الغرفة']);
        exit();
    }
    
    // Update room price
    $stmt = $pdo->prepare("UPDATE dorm_rooms SET price = ? WHERE room_id = ?");
    $stmt->execute([$price, $room_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث السعر بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

