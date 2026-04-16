<?php
// منع أي ظهور للأخطاء لضمان رد JSON نظيف
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// 1. التحقق من المستوى (المالك = 3)
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? 0;
    $dorm_id = (int)($_GET['dorm_id'] ?? 0);

    if ($dorm_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'معرف السكن غير صحيح']);
        exit();
    }

    // 2. التحقق من ملكية السكن
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);

    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح للوصول لهذا السكن']);
        exit();
    }

    // 3. الاستعلام المعدل لجلب الغرف مع تفاصيل الحاجزين
    // أضفنا استعلام فرعي لجلب الأسماء وحالات الحجز مفصولة بسطر جديد \n
    $sql = "SELECT 
            r.room_id,
            r.dorm_id,
            r.floor_id,
            r.room_number,
            r.room_type,
            r.price_per_month AS price, 
            r.position,
            r.view_type,
            r.is_available,
            r.capacity,
            r.features,
            (SELECT COUNT(*) FROM booking b WHERE b.room_id = r.room_id AND b.status IN ('Confirmed', 'Pending')) AS current_occupancy,
            (r.capacity - (SELECT COUNT(*) FROM booking b WHERE b.room_id = r.room_id AND b.status IN ('Confirmed', 'Pending'))) AS available_spots,
            (SELECT GROUP_CONCAT(CONCAT(l.name, ' (', b.status, ')') SEPARATOR '\n') 
             FROM booking b 
             JOIN login l ON b.student_id = l.users_id 
             WHERE b.room_id = r.room_id AND b.status IN ('Confirmed', 'Pending')) AS occupants
        FROM dorm_rooms r
        WHERE r.dorm_id = ?
        ORDER BY r.room_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dorm_id]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. معالجة البيانات
    foreach ($rooms as &$room) {
        $room['price'] = (float)($room['price'] ?? 0);
        $room['capacity'] = (int)($room['capacity'] ?? 0);
        $room['current_occupancy'] = (int)($room['current_occupancy'] ?? 0);
        $room['available_spots'] = (int)($room['available_spots'] ?? 0);
        
        // توليد الحالة برمجياً
        $room['status'] = ($room['is_available'] == 1) ? 'available' : 'booked';
        
        // التأكد من أن حقل occupants يحتوي على نص حتى لو لم يوجد حاجزين
        if (empty($room['occupants'])) {
            $room['occupants'] = 'لا يوجد حاجزين حالياً';
        }
    }

    echo json_encode([
        'success' => true,
        'rooms' => $rooms
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ تقني: ' . $e->getMessage()
    ]);
}
?>