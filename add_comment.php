<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['rating']) || !isset($data['comment'])) {
        throw new Exception('Missing required fields');
    }

    $dorm_id = isset($data['dorm_id']) && $data['dorm_id'] > 0 ? intval($data['dorm_id']) : null;
    $name = trim($data['name']);
    $rating = intval($data['rating']);
    $comment = trim($data['comment']);

    if (empty($name) || strlen($name) < 2 || strlen($name) > 30) {
        throw new Exception('Name must be between 2 and 30 characters');
    }

    if ($rating < 1 || $rating > 5) {
        throw new Exception('Rating must be between 1 and 5');
    }

    if (empty($comment) || strlen($comment) < 10 || strlen($comment) > 500) {
        throw new Exception('Comment must be between 10 and 500 characters');
    }

    if ($dorm_id !== null) {
        $checkDormQuery = "SELECT dorm_id FROM dorms WHERE dorm_id = :dorm_id";
        $checkStmt = $pdo->prepare($checkDormQuery);
        $checkStmt->bindParam(':dorm_id', $dorm_id, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            throw new Exception('Dorm not found');
        }
    }

    $insertQuery = "INSERT INTO comments (dorm_id, name, number_of_stars, comment, created_at)
                    VALUES (:dorm_id, :name, :rating, :comment, NOW())";

    $stmt = $pdo->prepare($insertQuery);

    if ($dorm_id !== null) {
        $stmt->bindParam(':dorm_id', $dorm_id, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(':dorm_id', null, PDO::PARAM_NULL);
    }

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $comment_id = $pdo->lastInsertId();

        $selectQuery = "SELECT id, dorm_id, name, number_of_stars, comment, created_at
                        FROM comments WHERE id = :id";
        $selectStmt = $pdo->prepare($selectQuery);
        $selectStmt->bindParam(':id', $comment_id, PDO::PARAM_INT);
        $selectStmt->execute();
        $newComment = $selectStmt->fetch(PDO::FETCH_ASSOC);

        $timestamp = strtotime($newComment['created_at']);
        $newComment['formatted_date'] = date('M d, Y', $timestamp);
        $newComment['time_ago'] = 'Just now';

        echo json_encode([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => $newComment
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('Failed to add comment');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>

