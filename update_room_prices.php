<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is owner
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $dorm_id = $input['dorm_id'] ?? null;
    $prices = $input['prices'] ?? [];
    
    if (!$dorm_id) {
        echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
        exit();
    }
    
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    
    // Verify ownership
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح']);
        exit();
    }
    
    // Update prices
    $stmt = $pdo->prepare("
        UPDATE dorm_room_prices 
        SET price = ? 
        WHERE dorm_id = ? AND room_type = ?
    ");
    
    foreach ($prices as $price_data) {
        $stmt->execute([
            $price_data['price'],
            $dorm_id,
            $price_data['room_type']
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث الأسعار بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

