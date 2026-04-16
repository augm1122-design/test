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
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    $dorm_id = $_GET['dorm_id'] ?? 0;

    // Verify owner owns this dorm
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);

    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح للوصول لهذا السكن']);
        exit();
    }

    // Check if available_rooms_view exists
    $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_graduation = 'available_rooms_view'");
    $viewExists = $stmt->rowCount() > 0;

    if ($viewExists) {
        // Get rooms from view
        $sql = "SELECT * FROM available_rooms_view WHERE dorm_id = ? ORDER BY room_type, room_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dorm_id]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Fallback: get rooms from dorm_rooms table if view doesn't exist
        $sql = "SELECT 
                dr.room_id,
                dr.dorm_id,
                dr.room_number,
                dr.room_type,
                dr.capacity,
                dr.current_occupancy,
                dr.price,
                dr.description,
                dr.status,
                (dr.capacity - dr.current_occupancy) as available_spots,
                CASE 
                    WHEN dr.current_occupancy >= dr.capacity THEN 'مكتملة'
                    WHEN dr.status = 'available' THEN 'متاحة'
                    ELSE 'غير متاحة'
                END as availability_status
                FROM dorm_rooms dr
                WHERE dr.dorm_id = ?
                ORDER BY dr.room_type, dr.room_number";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dorm_id]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get students in each room
    foreach ($rooms as &$room) {
        $stmt = $pdo->prepare("
            SELECT l.name, l.email, b.booking_date, b.status
            FROM booking b
            JOIN login l ON b.student_id = l.users_id
            WHERE b.room_id = ? AND b.status IN ('confirmed', 'pending')
            ORDER BY b.booking_date DESC
        ");
        $stmt->execute([$room['room_id']]);
        $room['students'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $room['student_count'] = count($room['students']);
    }

    echo json_encode([
        'success' => true,
        'rooms' => $rooms,
        'view_exists' => $viewExists
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

