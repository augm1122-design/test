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
    $input = json_decode(file_get_contents('php://input'), true);
    
    $student_email = $input['student_email'] ?? '';
    $subject = $input['subject'] ?? '';
    $message = $input['message'] ?? '';
    
    if (empty($student_email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'جميع الحقول مطلوبة']);
        exit();
    }
    
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];
    $owner_email = $_SESSION['user_email'] ?? '';
    
    // Get owner name
    $stmt = $pdo->prepare("SELECT name FROM login WHERE users_id = ?");
    $stmt->execute([$owner_id]);
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);
    $owner_name = $owner['name'] ?? 'المالك';
    
    // Get student info (use 'level' instead of 'user_level')
    $stmt = $pdo->prepare("SELECT users_id, name FROM login WHERE email = ? AND level = 2");
    $stmt->execute([$student_email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'الطالب غير موجود']);
        exit();
    }
    
    // Insert message into messages table
    // Using the actual column names from messages table
    $stmt = $pdo->prepare("
        INSERT INTO messages (sender_email, recipient_email, subject, message_body, sent_at, is_read)
        VALUES (?, ?, ?, ?, NOW(), 0)
    ");

    $stmt->execute([
        $owner_email,
        $student_email,
        $subject,
        $message
    ]);

    // Also insert into notifications table for the student
    $stmt = $pdo->query("SHOW COLUMNS FROM notifications");
    $notifColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (in_array('sender_id', $notifColumns)) {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, sender_id, sender_email, sender_name, subject, message, is_read, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
        ");

        $stmt->execute([
            $student['users_id'],
            $owner_id,
            $owner_email,
            $owner_name,
            $subject,
            $message
        ]);
    } else {
        // Simplified notification
        $notifCols = [];
        $notifVals = [];
        $notifParams = [];

        if (in_array('user_id', $notifColumns)) {
            $notifCols[] = 'user_id';
            $notifVals[] = '?';
            $notifParams[] = $student['users_id'];
        }
        if (in_array('message', $notifColumns)) {
            $notifCols[] = 'message';
            $notifVals[] = '?';
            $notifParams[] = $subject . ': ' . $message;
        }
        if (in_array('is_read', $notifColumns)) {
            $notifCols[] = 'is_read';
            $notifVals[] = '0';
        }

        if (count($notifCols) > 0) {
            $sql = "INSERT INTO notifications (" . implode(', ', $notifCols) . ") VALUES (" . implode(', ', $notifVals) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($notifParams);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إرسال الرسالة بنجاح'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

