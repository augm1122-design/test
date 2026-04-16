<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: log_in_student.php");
    exit();
}

// --- الجزء المسؤول عن التحديث (بدون ملفات خارجية) ---
if (isset($_GET['mark_read'])) {
    $msg_id = $_GET['mark_read'];
    $sql_update = "UPDATE messages SET is_read = 1 WHERE message_id = ? AND recipient_email = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$msg_id, $_SESSION['user_email']]);
    exit; // ننهي التنفيذ لأنه طلب خلفي فقط
}

try {
    $email = $_SESSION['user_email'];
    $sql = "SELECT message_id, sender_email, subject, message_body, sent_at, is_read FROM messages WHERE recipient_email = ? ORDER BY sent_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("خطأ في قاعدة البيانات: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البريد الوارد</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .inbox-container { background-color: #fff; padding: 20px 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 700px; text-align: right; }
        .inbox-container h2 { text-align: center; color: #333; }
        .message-list { margin-top: 20px; }
        .message-item { padding: 15px; border: 1px solid #eee; border-radius: 5px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; background-color: #f9f9f9; cursor: pointer; transition: 0.3s; }
        
        /* اللون الأزرق للرسائل غير المقروءة */
        .message-item.unread { background-color: #e6f7ff; border-color: #007bff; border-right: 5px solid #007bff; }
        
        .message-item .message-icon { font-size: 1.5rem; color: #007BFF; margin-left: 15px; }
        .message-item .message-content { flex-grow: 1; }
        .message-item h4 { margin: 0; color: #333; }
        .message-item p { margin: 5px 0 0 0; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .message-date { font-size: 0.8rem; color: #999; margin-right: 15px; }
        .back-button { display: block; width: 100%; padding: 10px; background-color: #6c757d; color: #fff; text-align: center; border-radius: 5px; text-decoration: none; margin-top: 20px; font-weight: bold; }
        .reply-btn { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 0.9rem; margin-top: 10px; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fff; margin: 5% auto; padding: 30px; border-radius: 8px; width: 90%; max-width: 600px; direction: rtl; text-align: right; }
        .close { color: #aaa; float: left; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="inbox-container">
        <h2>البريد الوارد</h2>
        <div class="message-list">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <div id="msg-<?php echo $message['message_id']; ?>" 
                         class="message-item <?php echo ($message['is_read'] == 0) ? 'unread' : ''; ?>" 
                         onclick="markAsRead(<?php echo $message['message_id']; ?>)">
                        
                        <i class="fas fa-envelope message-icon"></i>
                        <div class="message-content">
                            <h4><?php echo htmlspecialchars($message['subject']); ?></h4>
                            <p style="font-size: 0.85rem; color: #888; margin: 3px 0;">
                                <strong>من:</strong> <?php echo htmlspecialchars($message['sender_email'] ?? 'النظام'); ?>
                            </p>
                            <p><?php echo htmlspecialchars($message['message_body']); ?></p>
                            <?php if (!empty($message['sender_email'])): ?>
                                <button class="reply-btn" onclick="event.stopPropagation(); openReplyModal('<?php echo htmlspecialchars($message['sender_email']); ?>', '<?php echo htmlspecialchars($message['subject']); ?>')">
                                    <i class="fas fa-reply"></i> رد
                                </button>
                            <?php endif; ?>
                        </div>
                        <span class="message-date"><?php echo htmlspecialchars($message['sent_at']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-messages">لا توجد رسائل جديدة.</p>
            <?php endif; ?>
        </div>
        <a href="user_profile.php" class="back-button">العودة إلى الملف الشخصي</a>
    </div>

    <div id="replyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeReplyModal()">&times;</span>
            <h2>الرد على الرسالة</h2>
            <form id="replyForm" onsubmit="sendReply(event)">
                <input type="hidden" id="replyTo">
                <input type="hidden" id="replySubject">
                <div class="form-group"><textarea id="replyMessage" required placeholder="اكتب ردك هنا..."></textarea></div>
                <button type="submit" class="send-btn">إرسال الرد</button>
            </form>
        </div>
    </div>

    <script>
    // الدالة المسؤولة عن إزالة اللون الأزرق وتحديث القاعدة
    function markAsRead(id) {
        const row = document.getElementById('msg-' + id);
        if (row.classList.contains('unread')) {
            row.classList.remove('unread'); // شيل اللون فوراً
            // ابعث طلب سريع للسيرفر عشان يغير الحالة في القاعدة بدون ما تفتح صفحة جديدة
            fetch('?mark_read=' + id); 
        }
    }

    function openReplyModal(senderEmail, originalSubject) {
        document.getElementById('replyTo').value = senderEmail;
        document.getElementById('replySubject').value = 'Re: ' + originalSubject;
        document.getElementById('replyMessage').value = '';
        document.getElementById('replyModal').style.display = 'block';
    }

    function closeReplyModal() { document.getElementById('replyModal').style.display = 'none'; }

    async function sendReply(event) {
        event.preventDefault();
        const recipientEmail = document.getElementById('replyTo').value;
        const subject = document.getElementById('replySubject').value;
        const messageBody = document.getElementById('replyMessage').value;

        try {
            const response = await fetch('api/send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    recipient_email: recipientEmail,
                    subject: subject,
                    message_body: messageBody
                })
            });
            const data = await response.json();
            if (data.success) { alert('تم إرسال الرد بنجاح'); closeReplyModal(); }
        } catch (error) { alert('حدث خطأ'); }
    }

    window.onclick = function(event) { if (event.target == document.getElementById('replyModal')) closeReplyModal(); }
    </script>
</body>
</html>