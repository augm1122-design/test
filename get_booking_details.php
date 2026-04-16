<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// التحقق من أن المستخدم هو صاحب سكن (level = 3)
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $booking_id = $_GET['booking_id'] ?? 0;
    
    if (!$booking_id) {
        echo json_encode(['success' => false, 'message' => 'رقم الحجز مطلوب']);
        exit();
    }
    
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    
    // الاستعلام المعدل بأسماء الأعمدة الصحيحة
    $sql = "SELECT 
            b.*,
            l.name as student_name,
            l.email as student_email,
            l.phone as student_phone,
            l.national_id as student_national_id,
            l.gender as student_gender,
            l.specialty as student_specialty,
            d.name as dorm_name,
            d.location as dorm_location,
            dr.room_number,
            dr.room_type,
            dr.price_per_month as room_price -- تم التعديل هنا من price إلى price_per_month
            FROM booking b
            LEFT JOIN login l ON b.student_id = l.users_id
            LEFT JOIN dorm_rooms dr ON b.room_id = dr.room_id
            LEFT JOIN dorms d ON b.dorm_id = d.dorm_id
            WHERE b.booking_id = ? AND d.owner_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$booking_id, $owner_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'الحجز غير موجود أو غير مصرح']);
        exit();
    }
    
    echo json_encode([
        'success' => true,
        'booking' => $booking
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>