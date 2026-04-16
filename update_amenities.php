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
    $input = json_decode(file_get_contents('php://input'), true);
    $dorm_id = $input['dorm_id'] ?? null;
    $amenity_ids = $input['amenity_ids'] ?? [];
    
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
    
    // Delete all current amenities for this dorm
    $stmt = $pdo->prepare("DELETE FROM dorm_amenities WHERE dorm_id = ?");
    $stmt->execute([$dorm_id]);
    
    // Insert new amenities
    if (!empty($amenity_ids)) {
        $stmt = $pdo->prepare("INSERT INTO dorm_amenities (dorm_id, amenity_id) VALUES (?, ?)");
        foreach ($amenity_ids as $amenity_id) {
            $stmt->execute([$dorm_id, $amenity_id]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ المرافق بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

