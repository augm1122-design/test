<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: log_in_student.php");
    exit();
}

$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = htmlspecialchars($_GET['type']);
}

try {
    $email = $_SESSION['user_email'];

    $sql = "SELECT name, national_id, gender, phone, email, specialty FROM login WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $message = "حدث خطأ: لم يتم العثور على معلومات المستخدم.";
        $message_type = 'error';
    }

} catch (PDOException $e) {
    $message = "خطأ في قاعدة البيانات: " . $e->getMessage();
    $message_type = 'error';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الملف الشخصي</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 450px;
            text-align: right;
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .form-actions button, .form-actions a {
            flex-grow: 1;
            padding: 10px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .back-button {
            background-color: #6c757d;
            color: #fff;
        }
        .save-button {
            background-color: #007BFF;
            color: #fff;
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
    <div class="form-container">
        <h2>تعديل الملف الشخصي</h2>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($user): ?>
            <form action="edit_profile_process.php" method="POST">
                <div class="form-group">
                    <label for="name">الاسم الكامل:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="national_id">رقم الهوية:</label>
                    <input type="text" id="national_id" name="national_id" value="<?php echo htmlspecialchars($user['national_id']); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">الجنس:</label>
                    <select id="gender" name="gender">
                        <option value="Male" <?php echo ($user['gender'] == 'Male' ? 'selected' : ''); ?>>ذكر</option>
                        <option value="Female" <?php echo ($user['gender'] == 'Female' ? 'selected' : ''); ?>>أنثى</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">رقم الهاتف:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">البريد الإلكتروني:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="specialty">التخصص أو الوظيفة:</label>
                    <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($user['specialty']); ?>">
                </div>
                <div class="form-actions">
                    <a href="user_profile.php" class="back-button">إلغاء</a>
                    <button type="submit" class="save-button">حفظ التغييرات</button>
                </div>
            </form>
        <?php else: ?>
            <p>لم يتم العثور على معلومات المستخدم.</p>
            <div class="form-actions">
                <a href="dorm_type.php" class="back-button">العودة</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>