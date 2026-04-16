<?php
require_once '../../conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['dorm_id']) && isset($data['action'])) {
    $status = ($data['action'] === 'approve') ? 'active' : 'rejected';
    
    $stmt = $pdo->prepare("UPDATE dorms SET status = ? WHERE dorm_id = ?");
    $success = $stmt->execute([$status, $data['dorm_id']]);
    
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'بيانات ناقصة']);
}