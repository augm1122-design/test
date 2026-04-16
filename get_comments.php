<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is owner (use 'level' instead of 'user_level')
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    $dorm_id = $_GET['dorm_id'] ?? 0;

    // Verify owner owns this dorm
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);

    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح للوصول لهذا السكن']);
        exit();
    }

    // Get comments for this dorm with commenter details
    // First check what columns exist in comments table
    $stmt = $pdo->query("SHOW COLUMNS FROM comments");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Build SELECT query based on available columns
    $selectCols = ['c.*'];

    // Check for ID column
    $idCol = 'c.id';
    if (in_array('comment_id', $columns)) {
        $idCol = 'c.comment_id';
    } elseif (in_array('id', $columns)) {
        $idCol = 'c.id';
    }

    // Check for rating column
    if (in_array('number_of_stars', $columns)) {
        $selectCols[] = 'c.number_of_stars as rating';
    } elseif (in_array('rating', $columns)) {
        $selectCols[] = 'c.rating';
    }

    // Check for comment text column
    if (in_array('comment', $columns)) {
        $selectCols[] = 'c.comment as comment_text';
    } elseif (in_array('comment_text', $columns)) {
        $selectCols[] = 'c.comment_text';
    }

    // Add user info from login table
    $selectCols[] = 'l.name as user_name';
    $selectCols[] = 'l.email as user_email';

    $sql = "SELECT " . implode(', ', $selectCols) . "
            FROM comments c
            LEFT JOIN login l ON c.user_id = l.users_id
            WHERE c.dorm_id = ?
            ORDER BY c.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dorm_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

