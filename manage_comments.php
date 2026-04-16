<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_all':
            // Get all comments from all sources
            $sql = "SELECT c.id as comment_id, c.*,
                    d.name as dorm_name,
                    l.name as user_name,
                    c.number_of_stars as rating
                    FROM comments c
                    LEFT JOIN dorms d ON c.dorm_id = d.dorm_id
                    LEFT JOIN login l ON c.user_id = l.users_id
                    ORDER BY c.created_at DESC";
            $stmt = $pdo->query($sql);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add fallback for user names
            foreach ($comments as &$comment) {
                if (empty($comment['user_name']) && !empty($comment['name'])) {
                    $comment['user_name'] = $comment['name'];
                }
                if (empty($comment['user_name'])) {
                    $comment['user_name'] = 'غير معروف';
                }
                if (empty($comment['dorm_name'])) {
                    $comment['dorm_name'] = 'الصفحة الرئيسية';
                }
            }

            echo json_encode([
                'success' => true,
                'data' => $comments
            ]);
            break;
            
        case 'reply':
            // Reply to comment
            $input = json_decode(file_get_contents('php://input'), true);

            if (empty($input['comment_id']) || empty($input['reply'])) {
                echo json_encode(['success' => false, 'message' => 'معرف التعليق والرد مطلوبان']);
                exit();
            }

            // Update comment with admin reply - use 'id' column
            $stmt = $pdo->prepare("UPDATE comments SET admin_reply = ?, reply_date = NOW() WHERE id = ?");
            $stmt->execute([$input['reply'], $input['comment_id']]);

            echo json_encode([
                'success' => true,
                'message' => 'تم إضافة الرد بنجاح'
            ]);
            break;
            
        case 'delete':
            // Delete comment
            $input = json_decode(file_get_contents('php://input'), true);

            // Check if comment_id exists and is not empty
            if (!isset($input['comment_id']) || $input['comment_id'] === '' || $input['comment_id'] === null) {
                echo json_encode([
                    'success' => false,
                    'message' => 'معرف التعليق مطلوب',
                    'received_data' => $input
                ]);
                exit();
            }

            // Use 'id' column (not 'comment_id') as per database schema
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$input['comment_id']]);

            echo json_encode([
                'success' => true,
                'message' => 'تم حذف التعليق بنجاح'
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'إجراء غير صالح']);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

