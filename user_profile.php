<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: log_in_student.php");
    exit();
}

try {
    $email = $_SESSION['user_email'];

    $sql = "SELECT users_id, name, national_id, gender, phone, email, specialty FROM login WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("خطأ في قاعدة البيانات: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .profile-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px; /* تم زيادة العرض لاستيعاب الأزرار */
            text-align: right;
        }
        .profile-container h2 {
            text-align: center;
            color: #333;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            margin: 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .profile-info p strong {
            display: inline-block;
            width: 140px;
            color: #555;
        }
        .profile-actions {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }
        .profile-actions a {
            flex-grow: 1;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .back-button {
            background-color: #6c757d;
            color: #fff;
        }
        .edit-button {
            background-color: #28a745;
            color: #fff;
        }
        .inbox-button {
            background-color: #007BFF;
            color: #fff;
        }
        .bookings-button {
            background-color: #ffc107; /* لون مميز للحجوزات */
            color: #212529;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>معلومات المستخدم</h2>
        <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
            <p class="message <?php echo htmlspecialchars($_GET['type']); ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </p>
        <?php endif; ?>
        <?php if ($user): ?>
            <div class="profile-info">
                <p><strong>الاسم:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>رقم الهوية:</strong> <?php echo htmlspecialchars($user['national_id']); ?></p>
                <p><strong>الجنس:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                <p><strong>رقم الهاتف:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>التخصص أو الوظيفة:</strong> <?php echo htmlspecialchars($user['specialty']); ?></p>
            </div>
        <?php else: ?>
            <p>لم يتم العثور على معلومات المستخدم.</p>
        <?php endif; ?>
        <div class="profile-actions">
            <a href="home_page.php" class="back-button"><i class="fas fa-arrow-right"></i> العودة</a>
            <a href="edit_profile.php" class="edit-button"><i class="fas fa-edit"></i> تعديل</a>
            <a href="messages.php" class="inbox-button"><i class="fas fa-envelope"></i> البريد الوارد</a>
            <a href="my_bookings.php" class="bookings-button"><i class="fas fa-clipboard-list"></i> حجوزاتي</a>
        </div>
    </div>
</body>
</html>