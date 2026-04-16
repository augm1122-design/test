<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $sql = "SELECT d.*, l.name as owner_name, l.email as owner_email
            FROM dorms d
            LEFT JOIN login l ON d.owner_id = l.users_id
            ORDER BY d.dorm_id DESC";
    
    $stmt = $pdo->query($sql);
    $dorms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'dorms' => $dorms
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

