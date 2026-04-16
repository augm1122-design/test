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
    
    // Get students with confirmed bookings in this dorm
    $sql = "SELECT DISTINCT l.*, b.room_type
            FROM login l
            JOIN booking b ON l.users_id = b.student_id
            WHERE b.dorm_id = ? AND b.status = 'Confirmed'
            ORDER BY l.name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dorm_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'students' => $students
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

