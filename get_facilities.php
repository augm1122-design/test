<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is owner
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$dorm_id = $_GET['dorm_id'] ?? 0;

if (!$dorm_id) {
    echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
    exit();
}

try {
    // Check if dorm_facilities table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'dorm_facilities'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        // Get facilities from dorm_facilities table
        $stmt = $pdo->prepare("SELECT * FROM dorm_facilities WHERE dorm_id = ?");
        $stmt->execute([$dorm_id]);
        $facilities = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$facilities) {
            // Create default facilities record
            $stmt = $pdo->prepare("INSERT INTO dorm_facilities (dorm_id) VALUES (?)");
            $stmt->execute([$dorm_id]);
            
            $stmt = $pdo->prepare("SELECT * FROM dorm_facilities WHERE dorm_id = ?");
            $stmt->execute([$dorm_id]);
            $facilities = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        // If table doesn't exist, check dorms table for facility columns
        $stmt = $pdo->prepare("SELECT * FROM dorms WHERE dorm_id = ?");
        $stmt->execute([$dorm_id]);
        $dorm = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Extract facility-related columns
        $facilities = [];
        $facilityKeys = ['wifi', 'parking', 'laundry', 'gym', 'study_room', 'kitchen', 'security', 'cleaning'];
        
        foreach ($facilityKeys as $key) {
            $facilities[$key] = $dorm[$key] ?? 0;
        }
    }
    
    echo json_encode([
        'success' => true,
        'facilities' => $facilities
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

