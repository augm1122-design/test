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
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;

    if (!$owner_id) {
        echo json_encode(['success' => false, 'message' => 'لم يتم العثور على معرف المالك']);
        exit();
    }

    $dorm_id = $_GET['dorm_id'] ?? 0;
    
    // Verify owner owns this dorm
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح للوصول لهذا السكن']);
        exit();
    }
    
    // Get bookings for this dorm with all student information
    $sql = "SELECT b.*,
            l.name as student_name,
            l.email as student_email,
            l.phone as student_phone,
            b.total_price,
            b.room_type,
            b.booking_date,
            b.status
            FROM booking b
            JOIN login l ON b.student_id = l.users_id
            WHERE b.dorm_id = ?
            ORDER BY b.booking_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dorm_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'bookings' => $bookings
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

