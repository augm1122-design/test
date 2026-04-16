<?php
header('Content-Type: application/json');
require_once 'conn.php';

try {
    // Get parameters
    $dorm_id = isset($_GET['dorm_id']) ? intval($_GET['dorm_id']) : 0;
    $room_type = isset($_GET['room_type']) ? $_GET['room_type'] : '';
    $gender = isset($_GET['gender']) ? $_GET['gender'] : '';
    $current_user_id = isset($_GET['current_user_id']) ? intval($_GET['current_user_id']) : 0;
    
    if (!$dorm_id || !$room_type || !$gender) {
        echo json_encode([
            'success' => false,
            'error' => 'Missing required parameters'
        ]);
        exit;
    }
    
    // Get active roommate seekers for this dorm and room type
    $sql = "SELECT 
                rr.request_id,
                rr.student_id,
                rr.room_type,
                rr.specialty,
                rr.created_at,
                l.name as student_name,
                l.specialty as student_specialty,
                l.gender
            FROM roommate_requests rr
            INNER JOIN login l ON rr.student_id = l.users_id
            WHERE rr.dorm_id = :dorm_id
            AND rr.room_type = :room_type
            AND rr.gender = :gender
            AND rr.status = 'active'
            AND rr.student_id != :current_user_id
            ORDER BY rr.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':dorm_id' => $dorm_id,
        ':room_type' => $room_type,
        ':gender' => $gender,
        ':current_user_id' => $current_user_id
    ]);
    
    $seekers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the data (hide personal info, show only specialty)
    $formatted_seekers = array_map(function($seeker) {
        return [
            'request_id' => $seeker['request_id'],
            'student_id' => $seeker['student_id'],
            'specialty' => $seeker['student_specialty'] ?: $seeker['specialty'] ?: 'Not specified',
            'room_type' => $seeker['room_type'],
            'gender' => $seeker['gender'],
            'days_ago' => floor((time() - strtotime($seeker['created_at'])) / 86400)
        ];
    }, $seekers);
    
    echo json_encode([
        'success' => true,
        'seekers' => $formatted_seekers,
        'count' => count($formatted_seekers)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>

