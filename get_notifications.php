<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// 1. التحقق من صلاحية المستخدم (يجب أن يكون صاحب سكن)
$user_level = $_SESSION['user_level'] ?? $_SESSION['level'] ?? 0;
if ($user_level != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

// 2. جلب البيانات الأساسية من الجلسة (Session)
$owner_id = $_SESSION['users_id'] ?? $_SESSION['user_id'] ?? 0;
$owner_email = $_SESSION['user_email'] ?? '';

try {
    $all_notifications = [];

    // --- الجزء الأول: جلب الرسائل (Messages) ---
    // تم إضافة COLLATE لحل مشكلة تعارض الترميز بين الجداول
    $sql_messages = "SELECT m.*, l.name as sender_name 
                     FROM messages m
                     LEFT JOIN login l ON m.sender_email COLLATE utf8mb4_general_ci = l.email COLLATE utf8mb4_general_ci
                     WHERE m.recipient_id = ? OR m.recipient_email = ?
                     ORDER BY m.sent_at DESC 
                     LIMIT 50";
    
    $stmt1 = $pdo->prepare($sql_messages);
    $stmt1->execute([$owner_id, $owner_email]);
    $messages = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($messages as $msg) {
        $all_notifications[] = [
            'id' => $msg['message_id'],
            'sender_name' => $msg['sender_name'] ?? $msg['sender_email'],
            'sender_email' => $msg['sender_email'] ?? '',
            'subject' => $msg['subject'] ?? 'رسالة جديدة',
            'message' => $msg['message_body'] ?? '',
            'created_at' => $msg['sent_at'] ?? '',
            'is_read' => $msg['is_read'] ?? 0,
            'type' => 'message'
        ];
    }

    // --- الجزء الثاني: جلب التنبيهات (Notifications) ---
    $sql_notifs = "SELECT * FROM notifications 
                   WHERE user_id = ? 
                   ORDER BY created_at DESC 
                   LIMIT 50";
    
    $stmt2 = $pdo->prepare($sql_notifs);
    $stmt2->execute([$owner_id]);
    $notifs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($notifs as $notif) {
        $all_notifications[] = [
            'id' => $notif['notification_id'],
            'sender_name' => 'تنبيه النظام',
            'sender_email' => '',
            'subject' => $notif['title'] ?? 'إشعار',
            'message' => $notif['message'] ?? '',
            'created_at' => $notif['created_at'] ?? '',
            'is_read' => $notif['is_read'] ?? 0,
            'type' => $notif['type'] ?? 'notification'
        ];
    }

    // 3. ترتيب جميع البيانات (رسائل + تنبيهات) حسب التاريخ - الأحدث أولاً
    usort($all_notifications, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    // 4. حساب عدد العناصر غير المقروءة لتحديث رقم الجرس
    $unread_count = 0;
    foreach ($all_notifications as $item) {
        if ($item['is_read'] == 0) {
            $unread_count++;
        }
    }

    // 5. إرسال النتيجة النهائية
    echo json_encode([
        'success' => true,
        'notifications' => $all_notifications,
        'unread_count' => $unread_count
    ]);

} catch (PDOException $e) {
    // في حال حدوث خطأ، يتم إرساله بصيغة JSON ليفهمه الجافا سكريبت ولا يتوقف الجرس
    echo json_encode([
        'success' => false, 
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}