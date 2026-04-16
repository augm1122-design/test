<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is owner
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;

    if (!$owner_id) {
        echo json_encode(['success' => false, 'message' => 'لم يتم العثور على معرف المالك']);
        exit();
    }

    if (empty($input['booking_id']) || empty($input['status'])) {
        echo json_encode(['success' => false, 'message' => 'معرف الحجز والحالة مطلوبان']);
        exit();
    }

    $booking_id = $input['booking_id'];
    $status = $input['status'];

    // Get booking details and verify owner owns the dorm
    $stmt = $pdo->prepare("SELECT b.*, d.owner_id, d.name as dorm_name,
                           l.name as student_name, l.email as student_email
                           FROM booking b
                           JOIN dorms d ON b.dorm_id = d.dorm_id
                           LEFT JOIN login l ON b.student_id = l.users_id
                           WHERE b.booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();

    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'الحجز غير موجود']);
        exit();
    }

    if ($booking['owner_id'] != $owner_id) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح لتعديل هذا الحجز']);
        exit();
    }

    // Update booking status
    $sql = "UPDATE booking SET status = :status, owner_notes = :notes WHERE booking_id = :booking_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':notes' => $input['notes'] ?? '',
        ':booking_id' => $booking_id
    ]);

    // Create notification and email for student
    $notification_message = '';
    $email_subject = '';
    $email_body = '';

    if ($status == 'Confirmed') {
        $notification_message = "تم تأكيد حجزك في {$booking['dorm_name']} من قبل المالك";
        $email_subject = "تأكيد الحجز - {$booking['dorm_name']}";
        $email_body = "عزيزي/عزيزتي {$booking['student_name']},\n\n";
        $email_body .= "نود إعلامك بأنه تم تأكيد حجزك في {$booking['dorm_name']} من قبل مالك السكن.\n\n";
        $email_body .= "تفاصيل الحجز:\n";
        $email_body .= "- رقم الحجز: {$booking['booking_id']}\n";
        $email_body .= "- السكن: {$booking['dorm_name']}\n";
        $email_body .= "- تاريخ الدخول: {$booking['check_in_date']}\n";
        $email_body .= "- تاريخ الخروج: {$booking['check_out_date']}\n\n";
        if (!empty($input['notes'])) {
            $email_body .= "ملاحظات المالك: {$input['notes']}\n\n";
        }
        $email_body .= "شكراً لاستخدامك نظام حجز السكنات الجامعية.";
    } elseif ($status == 'Cancelled' || $status == 'Rejected') {
        $notification_message = "تم رفض حجزك في {$booking['dorm_name']} من قبل المالك";
        $email_subject = "رفض الحجز - {$booking['dorm_name']}";
        $email_body = "عزيزي/عزيزتي {$booking['student_name']},\n\n";
        $email_body .= "نأسف لإبلاغك بأنه تم رفض حجزك في {$booking['dorm_name']} من قبل مالك السكن.\n\n";
        if (!empty($input['notes'])) {
            $email_body .= "السبب: {$input['notes']}\n\n";
        }
        $email_body .= "يمكنك البحث عن سكنات أخرى متاحة في النظام.";
    }

    if ($notification_message) {
        // Create notification
        $notif_sql = "INSERT INTO notifications (user_id, title, message, type, created_at)
                      VALUES (:user_id, 'تحديث حالة الحجز', :message, 'booking', NOW())";
        $notif_stmt = $pdo->prepare($notif_sql);
        $notif_stmt->execute([
            ':user_id' => $booking['student_id'],
            ':message' => $notification_message
        ]);

        // Send email
        if (!empty($email_subject) && !empty($email_body) && !empty($booking['student_email'])) {
            $to = $booking['student_email'];
            $headers = "From: noreply@dorms.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            @mail($to, $email_subject, $email_body, $headers);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث حالة الحجز بنجاح وإرسال إشعار للطالب'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

