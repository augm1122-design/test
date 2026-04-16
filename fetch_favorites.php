<?php

session_start(); 
require_once 'conn.php';
header('Content-Type: application/json'); 

// التأكد من أن المستخدم مسجل دخوله
$student_id = $_SESSION['student_id'] ?? null;

if ($student_id === null) {
    // إذا لم يكن المستخدم مسجل دخول أعد قائمة فارغة من السكنات
    echo json_encode(['success' => true, 'dorms' => [], 'message' => 'User not logged in.']);
    exit;
}

$favorites = []; 
try {
    
    $stmt = $pdo->prepare("
        SELECT
            d.dorm_id,
            d.name,
            d.location,
            d.gender,
            d.proximity_to_university,
            d.image_url,
            GROUP_CONCAT(DISTINCT da.amenity_id) AS amenity_ids,
            GROUP_CONCAT(DISTINCT a.amenity_name SEPARATOR ', ') AS dorm_amenities_list,
            GROUP_CONCAT(DISTINCT drp.room_type SEPARATOR ', ') AS room_types_available,
            GROUP_CONCAT(DISTINCT CONCAT(drp.room_type, ': $', drp.price) SEPARATOR '; ') AS prices_details
        FROM
            favorites f
        JOIN
            dorms d ON f.dorm_id = d.dorm_id
        LEFT JOIN
            dorm_amenities da ON d.dorm_id = da.dorm_id
        LEFT JOIN
            amenities a ON da.amenity_id = a.amenity_id
        LEFT JOIN
            dorm_room_prices drp ON d.dorm_id = drp.dorm_id
        WHERE
            f.student_id = :student_id
        GROUP BY
            d.dorm_id
        ORDER BY
            d.name ASC
    ");
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'dorms' => $favorites]);

} catch (PDOException $e) {
    error_log("Database error in fetch_favorites.php: " . $e->getMessage()); 
    echo json_encode(['success' => false, 'message' => 'Error fetching favorite dorms from the database.']);
} catch (Exception $e) {
    error_log("General error in fetch_favorites.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred in fetch_favorites.php.']);
}
?>