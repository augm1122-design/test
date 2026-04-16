<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input['users_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID مطلوب']);
                exit();
            }
            // تحديث الاسم والهاتف للطالب
            $stmt = $pdo->prepare("UPDATE login SET name = ?, phone = ? WHERE users_id = ? AND level = 2");
            $stmt->execute([$input['name'], $input['phone'], $input['users_id']]);
            echo json_encode(['success' => true, 'message' => 'تم تحديث الطالب']);
            break;

        case 'delete':
            $input = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("DELETE FROM login WHERE users_id = ? AND level = 2");
            $stmt->execute([$input['users_id']]);
            echo json_encode(['success' => true, 'message' => 'تم حذف الطالب']);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'إجراء غير صالح']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>