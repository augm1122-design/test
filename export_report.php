<?php
session_start();
require_once '../../conn.php';

// Check if user is admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    die('Unauthorized');
}

$type = $_GET['type'] ?? 'bookings';

try {
    if ($type === 'bookings') {
        // Export bookings report
        $stmt = $pdo->query("
            SELECT
                b.booking_id,
                b.booking_date,
                l.name as student_name,
                l.email as student_email,
                d.name as dorm_name,
                dr.room_number,
                b.check_in_date,
                b.check_out_date,
                b.total_price,
                b.status,
                b.payment_status
            FROM booking b
            LEFT JOIN login l ON b.student_id = l.users_id
            LEFT JOIN dorms d ON b.dorm_id = d.dorm_id
            LEFT JOIN dorm_rooms dr ON b.room_id = dr.room_id
            ORDER BY b.booking_date DESC
        ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="bookings_report_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($output, [
            'رقم الحجز',
            'تاريخ الحجز',
            'اسم الطالب',
            'البريد الإلكتروني',
            'اسم السكن',
            'رقم الغرفة',
            'تاريخ الدخول',
            'تاريخ الخروج',
            'السعر الإجمالي',
            'الحالة',
            'حالة الدفع'
        ]);
        
        // Add data
        foreach ($data as $row) {
            fputcsv($output, [
                $row['booking_id'],
                $row['booking_date'],
                $row['student_name'],
                $row['student_email'],
                $row['dorm_name'],
                $row['room_number'],
                $row['check_in_date'],
                $row['check_out_date'],
                $row['total_price'],
                $row['status'],
                $row['payment_status']
            ]);
        }
        
        fclose($output);
        
    } elseif ($type === 'students') {
        // Export students report
        $stmt = $pdo->query("
            SELECT
                l.users_id as student_id,
                l.name,
                l.email,
                l.phone,
                l.university,
                l.major,
                l.gender,
                COUNT(b.booking_id) as total_bookings,
                SUM(CASE WHEN b.status = 'Confirmed' THEN 1 ELSE 0 END) as confirmed_bookings
            FROM login l
            LEFT JOIN booking b ON l.users_id = b.student_id
            WHERE l.level = 2
            GROUP BY l.users_id
            ORDER BY l.name
        ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="students_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'رقم الطالب',
            'الاسم',
            'البريد الإلكتروني',
            'الهاتف',
            'الجامعة',
            'التخصص',
            'الجنس',
            'إجمالي الحجوزات',
            'الحجوزات المؤكدة'
        ]);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['student_id'],
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['university'],
                $row['major'],
                $row['gender'],
                $row['total_bookings'],
                $row['confirmed_bookings']
            ]);
        }
        
        fclose($output);
        
    } elseif ($type === 'dorms') {
        // Export dorms report
        $stmt = $pdo->query("
            SELECT 
                d.dorm_id,
                d.name,
                d.location,
                d.gender,
                d.owner_name,
                d.owner_email,
                l.phone as owner_phone,
                COUNT(DISTINCT dr.room_id) as total_rooms,
                COUNT(DISTINCT b.booking_id) as total_bookings,
                SUM(CASE WHEN b.status = 'Confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                d.rating,
                d.status
            FROM dorms d
            LEFT JOIN login l ON d.owner_id = l.users_id
            LEFT JOIN dorm_rooms dr ON d.dorm_id = dr.dorm_id
            LEFT JOIN booking b ON d.dorm_id = b.dorm_id
            GROUP BY d.dorm_id
            ORDER BY d.name
        ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="dorms_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'رقم السكن',
            'الاسم',
            'الموقع',
            'الجنس',
            'اسم المالك',
            'بريد المالك',
            'هاتف المالك',
            'عدد الغرف',
            'إجمالي الحجوزات',
            'الحجوزات المؤكدة',
            'التقييم',
            'الحالة'
        ]);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['dorm_id'],
                $row['name'],
                $row['location'],
                $row['gender'],
                $row['owner_name'],
                $row['owner_email'],
                $row['owner_phone'],
                $row['total_rooms'],
                $row['total_bookings'],
                $row['confirmed_bookings'],
                $row['rating'],
                $row['status']
            ]);
        }
        
        fclose($output);
    }
    
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>

