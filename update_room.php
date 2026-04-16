<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// التحقق من المستوى (المالك = 3)
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $room_id = $input['room_id'] ?? 0;
    $room_number = $input['room_number'] ?? '';
    $room_type = $input['room_type'] ?? '';
    $price = $input['price'] ?? 0;
    
    if (!$room_id) {
        echo json_encode(['success' => false, 'message' => 'معرف الغرفة مطلوب']);
        exit();
    }
    
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    
    // التأكد من أن المالك يمتلك هذا السكن
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
    
    // التعديل هنا: استخدام price_per_month بدلاً من price
    // وحذف updated_at لأنه غير موجود في جدول dorm_rooms
    $stmt = $pdo->prepare("
        UPDATE dorm_rooms 
        SET room_number = ?, 
            room_type = ?, 
            price_per_month = ? 
        WHERE room_id = ?
    ");
    
    $result = $stmt->execute([$room_number, $room_type, $price, $room_id]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'تم تحديث معلومات الغرفة بنجاح'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'لم يتم إجراء أي تغييرات']);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>