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

    // Get owner email
    $stmt = $pdo->prepare("SELECT email FROM login WHERE users_id = ?");
    $stmt->execute([$owner_id]);
    $owner = $stmt->fetch();
    $owner_email = $owner['email'] ?? 'owner@dorms.com';

    $dorm_id = $input['dorm_id'] ?? 0;

    // Verify owner owns this dorm
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);

    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح']);
        exit();
    }

    $type = $input['type'];
    $subject = $input['subject'];
    $body = $input['body'];

    // Get recipients
    $recipients = [];

    if ($type === 'all_students') {
        // Get all students in this dorm
        $stmt = $pdo->prepare("SELECT DISTINCT l.email, l.users_id, l.name
                               FROM login l
                               JOIN booking b ON l.users_id = b.student_id
                               WHERE b.dorm_id = ? AND b.status = 'Confirmed'");
        $stmt->execute([$dorm_id]);
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($type === 'single_student') {
        $stmt = $pdo->prepare("SELECT email, users_id, name FROM login WHERE email = ?");
        $stmt->execute([$input['recipient']]);
        $recipient = $stmt->fetch();
        if ($recipient) {
            $recipients[] = $recipient;
        }
    }

    if (empty($recipients)) {
        echo json_encode(['success' => false, 'message' => 'لا يوجد مستلمين']);
        exit();
    }

    // Send messages
    $sql = "INSERT INTO messages (sender_email, recipient_email, recipient_id, subject, message_body, sent_at)
            VALUES (:sender_email, :recipient_email, :recipient_id, :subject, :message_body, NOW())";

    $stmt = $pdo->prepare($sql);

    $sent_count = 0;
    foreach ($recipients as $recipient) {
        try {
            $stmt->execute([
                ':sender_email' => $owner_email,
                ':recipient_email' => $recipient['email'],
                ':recipient_id' => $recipient['users_id'],
                ':subject' => $subject,
                ':message_body' => $body
            ]);

            // Create notification
            $notif_sql = "INSERT INTO notifications (user_id, title, message, type, created_at)
                          VALUES (:user_id, :title, :message, 'message', NOW())";
            $notif_stmt = $pdo->prepare($notif_sql);
            $notif_stmt->execute([
                ':user_id' => $recipient['users_id'],
                ':title' => 'رسالة جديدة',
                ':message' => 'لديك رسالة جديدة: ' . $subject
            ]);

            // Send actual email
            $to = $recipient['email'];
            $email_subject = $subject;
            $email_body = "مرحباً " . ($recipient['name'] ?? '') . ",\n\n" . $body . "\n\n---\nهذه رسالة من نظام حجز السكنات الجامعية";
            $headers = "From: " . $owner_email . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            @mail($to, $email_subject, $email_body, $headers);

            $sent_count++;
        } catch (PDOException $e) {
            // Continue with other recipients
            continue;
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'تم إرسال الرسالة بنجاح إلى ' . $sent_count . ' مستلم'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

