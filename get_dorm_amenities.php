<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    if (!isset($_GET['dorm_id'])) {
        echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
        exit();
    }
    
    $dorm_id = $_GET['dorm_id'];
    
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

