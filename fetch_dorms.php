<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conn.php'; 

session_start();

// المتغيرات للفلترة
$search_query = isset($_GET['search']) ? mb_strtolower(trim($_GET['search']), 'UTF-8') : '';
$price_filter = isset($_GET['price_range']) ? trim($_GET['price_range']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$room_type_filter = isset($_GET['room_type']) ? trim($_GET['room_type']) : '';
$amenities = isset($_GET['amenities']) ? (array)$_GET['amenities'] : []; 
$gender_filter = isset($_GET['gender']) ? trim($_GET['gender']) : ''; 
$lang = isset($_GET['lang']) ? trim($_GET['lang']) : 'en'; 

$student_id = $_SESSION['student_id'] ?? null;

// اختيار الحقول بناءً على اللغة
$nameField = $lang === 'ar' ? 'COALESCE(d.name_ar, d.name)' : 'd.name';
$descField = $lang === 'ar' ? 'COALESCE(d.description_ar, d.description)' : 'd.description';
$amenityField = $lang === 'ar' ? 'COALESCE(A.amenity_name_ar, A.amenity_name)' : 'A.amenity_name';

$sql = "SELECT d.dorm_id, d.image_url, d.location, d.price_range, d.gender, d.status,
        $nameField AS name,
        $descField AS description,
        f.dorm_id IS NOT NULL AS is_favorite,
        GROUP_CONCAT(DISTINCT FDRP.room_type) AS room_types_available,
        GROUP_CONCAT(DISTINCT $amenityField ORDER BY $amenityField) AS dorm_amenities_list
        FROM dorms d";

$sql .= " LEFT JOIN dorm_room_prices FDRP ON d.dorm_id = FDRP.dorm_id";
$sql .= " LEFT JOIN dorm_amenities FDA ON d.dorm_id = FDA.dorm_id";
$sql .= " LEFT JOIN amenities A ON FDA.amenity_id = A.amenity_id";

$where_clauses = [];
$params = [];


$where_clauses[] = "d.status = 'active'";

// فلترة حسب الجنس
if (!empty($gender_filter)) {
    $where_clauses[] = "d.gender = :gender_filter";
    $params[':gender_filter'] = $gender_filter;
}

if (!empty($search_query)) {
    $where_clauses[] = "LOWER(d.name) LIKE :search_query";
    $params[':search_query'] = '%' . $search_query . '%';
}

if (!empty($price_filter)) {
    $where_clauses[] = "d.price_range = :price_filter";
    $params[':price_filter'] = $price_filter;
}

if (!empty($location_filter)) {
    $where_clauses[] = "d.location = :location_filter";
    $params[':location_filter'] = $location_filter;
}

if (!empty($room_type_filter)) {
    $where_clauses[] = "FDRP.room_type = :room_type_filter";
    $params[':room_type_filter'] = $room_type_filter;
}

if (!empty($amenities)) {
    $amenity_placeholders = [];
    foreach ($amenities as $index => $amenity_id) {
        $placeholder = ":amenity_id_" . $index;
        $amenity_placeholders[] = $placeholder;
        $params[$placeholder] = (int)$amenity_id;
    }
    $where_clauses[] = "FDA.amenity_id IN (" . implode(',', $amenity_placeholders) . ")";
}

$sql .= " LEFT JOIN favorites f ON d.dorm_id = f.dorm_id AND f.student_id = :student_id_for_favorite";
$params[':student_id_for_favorite'] = $student_id;

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " GROUP BY d.dorm_id";

if (!empty($amenities)) {
    $sql .= " HAVING COUNT(DISTINCT FDA.amenity_id) = :amenities_count";
    $params[':amenities_count'] = count($amenities);
}

$sql .= " ORDER BY d.name ASC";

try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $param_type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $param_type);
    }
    $stmt->execute();
    $dorms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($dorms) {
        echo json_encode(['success' => true, 'dorms' => $dorms]);
    } else {
        echo json_encode(['success' => true, 'dorms' => [], 'message' => 'No dorms found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>