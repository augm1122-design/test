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
    $sql = "SELECT l.users_id, l.name, l.email, l.phone,
            (SELECT COUNT(*) FROM dorms WHERE owner_id = l.users_id) as dorm_count
            FROM login l
            WHERE l.level = 3
            ORDER BY l.users_id DESC";

    $stmt = $pdo->query($sql);
    $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'owners' => $owners
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

