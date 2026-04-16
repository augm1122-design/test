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
    $dorm_id = $_GET['dorm_id'] ?? null;
    
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
    
    // Get all available amenities
    $stmt = $pdo->query("SELECT * FROM amenities ORDER BY amenity_name");
    $all_amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get dorm's current amenities
    $stmt = $pdo->prepare("
        SELECT amenity_id 
        FROM dorm_amenities 
        WHERE dorm_id = ?
    ");
    $stmt->execute([$dorm_id]);
    $dorm_amenities = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Mark which amenities are selected
    foreach ($all_amenities as &$amenity) {
        $amenity['is_selected'] = in_array($amenity['amenity_id'], $dorm_amenities);
    }
    
    echo json_encode([
        'success' => true,
        'amenities' => $all_amenities
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

