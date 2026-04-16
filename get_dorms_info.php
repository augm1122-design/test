<?php
require_once 'conn.php';

// Get all dorms with their room prices
$query = "
    SELECT 
        d.dorm_id,
        d.name,
        d.gender,
        d.location,
        GROUP_CONCAT(CONCAT(drp.room_type, ':', drp.price) SEPARATOR '|') as room_prices
    FROM dorms d
    LEFT JOIN dorm_room_prices drp ON d.dorm_id = drp.dorm_id
    GROUP BY d.dorm_id, d.name, d.gender, d.location
    ORDER BY d.dorm_id
";

$stmt = $pdo->query($query);
$dorms = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total Dorms: " . count($dorms) . "\n\n";

foreach ($dorms as $dorm) {
    echo "ID: {$dorm['dorm_id']}\n";
    echo "Name: {$dorm['name']}\n";
    echo "Gender: {$dorm['gender']}\n";
    echo "Location: {$dorm['location']}\n";
    echo "Room Prices: {$dorm['room_prices']}\n";
    echo "---\n";
}
?>

