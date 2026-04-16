<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح - يجب تسجيل الدخول']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['booking_id'])) {
        echo json_encode(['success' => false, 'message' => 'معرف الحجز مطلوب']);
        exit();
    }
    
    $booking_id = $input['booking_id'];
    $user_email = $_SESSION['user_email'];
    
    // Verify that this booking belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM booking WHERE booking_id = ? AND email = ?");
    $stmt->execute([$booking_id, $user_email]);
    $booking = $stmt->fetch();
    
    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'الحجز غير موجود أو لا تملك صلاحية إلغائه']);
        exit();
    }
    
    // Check if booking is already cancelled
    if (strtolower($booking['status']) == 'cancelled') {
        echo json_encode(['success' => false, 'message' => 'الحجز ملغي مسبقاً']);
        exit();
    }
    
    // Delete the booking from database
    $stmt = $pdo->prepare("DELETE FROM booking WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    
    // If booking had a room_id, update room availability
    if (!empty($booking['room_id'])) {
        // Decrease current_occupancy and increase available_spots
        $stmt = $pdo->prepare("
            UPDATE dorm_rooms 
            SET current_occupancy = GREATEST(0, current_occupancy - 1),
                available_spots = LEAST(capacity, available_spots + 1),
                is_available = 1
            WHERE room_id = ?
        ");
        $stmt->execute([$booking['room_id']]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إلغاء الحجز بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

