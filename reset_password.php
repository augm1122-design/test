<?php

require_once 'conn.php';

$token = $_GET['token'] ?? ''; // الحصول على التوكن من الرابط
$email = $_GET['email'] ?? ''; // الحصول على البريد الإلكتروني من الرابط

$message = '';
$message_type = '';
$show_form = false; // التحكم في إظهار نموذج تعيين كلمة المرور

date_default_timezone_set('Asia/Amman');

// التحقق من ان التوكن والبريد الإلكتروني موجودان في الرابط
if (!empty($token) && !empty($email)) {
    try {
        // التحقق من التوكن في جدول password_resets
        // يجب أن يكون التوكن موجودًا، مرتبطًا بالبريد الإلكتروني، لم تنتهِ صلاحيته، ولم يُستخدم بعد.
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND email = ? AND expires_at > NOW() AND used = 0");
        $stmt->execute([$token, $email]);
        $reset_request = $stmt->fetch();

        // التحقق من نتيجة الاستعلام
        if ($reset_request) {
            // اذا كان التوكن صالح 
            $show_form = true;//يتم اظهار نموذج تعيين كلمة المرور
            $message = 'Please enter and confirm your new password. ';
            $message_type = 'success';//نوع الرسالة للتحكم في لونها

        } else {//إذا كان التوكن غير صالح
            $message = 'The password reset link is invalid, expired, or has already been used.';
            $message_type = 'error';
        }

    } catch (PDOException $e) {//التعامل مع أخطاء قاعدة البيانات
        $message = 'A database error occurred while verifying the link: ' . $e->getMessage(); // عرض الخطأ لأغراض التصحيح
        $message_type = 'error';
    }

} else { //اذا لم يكن التوكن أو البريد الإلكتروني موجودين في الرابط
    $message = 'The password reset link is incomplete. Please use the link that was sent to your email.';
    $message_type = 'error';
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
    // جلب البيانات من النموذج
    $token = $_POST['token'] ?? '';
    $email = $_POST['email'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // التحقق من صحة المدخلات
    if (empty($new_password) || empty($confirm_password)) {
        $message = 'Please enter and confirm your new password.';
        $message_type = 'error';
        $show_form = true; // إبقاء النموذج ظاهرًا إذا فشل التحقق

        // التحقق من تطابق كلمات المرور
    } elseif ($new_password !== $confirm_password) {
        $message = 'Passwords do not match.';
        $message_type = 'error';
        $show_form = true;

        // التحقق من قوة كلمة المرور
    } elseif (strlen($new_password) < 8) { 
        $message = 'Password must be at least 8 characters long.';
        $message_type = 'error';
        $show_form = true;

        // اذا كانت جميع المدخلات صحيحة
    } else {
        try {
            // التحقق النهائي من التوكن قبل التحديث لمنع أي تلاعب
            $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND email = ? AND expires_at > NOW() AND used = 0");
            $stmt->execute([$token, $email]);
            $reset_request = $stmt->fetch();

            // اذا كان التوكن صالحًا تحديث كلمة المرور
            if ($reset_request) {
                // تشفير كلمة المرور الجديدة قبل تخزينها
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // بدء معاملة لضمان التزامن
                $pdo->beginTransaction();

                // تحديث كلمة المرور في جدول login
                $stmt = $pdo->prepare("UPDATE login SET password = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $email]);

                // وضع علامة على التوكن بأنه "مستخدم" في جدول password_resets لمنع إعادة استخدامه
                $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
                $stmt->execute([$token]);

                // تأكيد المعاملة
                $pdo->commit();

                $message = 'The password has been successfully reset. You can now log in with your new password.';
                $message_type = 'success';
                $show_form = false; // إخفاء النموذج بعد إعادة التعيين الناجحة
            } else {
                $message = 'An error occurred. The password reset link is invalid or has expired.';
                $message_type = 'error';
                $show_form = false; // إخفاء النموذج في حالة وجود توكن غير صالح
            }
        } catch (PDOException $e) {
            $pdo->rollBack(); // التراجع عن المعاملة في حالة حدوث خطأ
            $message = 'A database error occurred while updating the password: ' . $e->getMessage();
            $message_type = 'error';
            $show_form = true; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Reset Password | MU-DORMS</title>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
 <style>
   * {
     box-sizing: border-box;
      margin: 0;
       padding: 0;
     }

   body
    { 
        font-family: 'Poppins', sans-serif;
         background-color: #f9f9f9;
          color: #212529;
         }

   header
    { 
        background-color: #fff;
         padding: 20px 40px;
          display: flex;
           justify-content: space-between;
            align-items: center;
             box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
            }

   .logo 
   {
     font-weight: 700;
      font-size: 1.5rem;
       text-decoration: none;
        color: #000;
     }

   nav ul
    {
         list-style: none;
          display: flex;
           gap: 30px; 
        }

   nav ul li a
    { 
        text-decoration: none;
         color: #333; 
         font-weight: 500;
          transition: color 0.3s; 
        }

   nav ul li a:hover
    {
         color: #007bff; 
        }

   .sign-up-btn 
   {
     padding: 8px 20px;
      background-color: #e9ecef;
       border-radius: 25px;
        text-decoration: none;
         font-weight: 500;
          color: #333;
         }

   .main-container
    {
         display: flex;
          flex-wrap: wrap; 
          justify-content: center;
           align-items: flex-start;
            padding: 60px 20px;
             max-width: 1200px;
              margin: auto;
               gap: 40px; 
               min-height: calc(100vh - 80px); 
            }

   .form-container
    {
         flex: 1 1 350px;
          max-width: 450px;
           background-color: #fff;
            padding: 40px; 
            border-radius: 12px;
             box-shadow: 0 4px 8px rgba(0,0,0,0.05);
             }

   .form-container h2
    {
         margin-bottom: 30px;
          font-size: 1.8rem;
           text-align: center;
         }

   form 
   {
     display: flex; 
     flex-direction: column;
      gap: 15px;
     }

   input[type="password"] 
   {
     padding: 12px 15px;
      border-radius: 12px;
       border: 1px solid #dcdcdc; 
       background-color: #f0f2f5; 
       font-size: 1rem;
        width: 100%;
     }

   .btn
    {
         padding: 12px;
          font-size: 1rem;
           border: none;
            border-radius: 25px;
             font-weight: 500;
              cursor: pointer; 
              transition: background-color 0.3s;
               text-align: center;
                display: block;
                 width: 100%;
                }

   .btn-primary 
   { 
    background-color: #357edd;
     color: white;
    }

   .btn-primary:hover 
   {
     background-color: #245cbf; 
    }

   .message
    {
       text-align: center;
       margin-top: 20px;
       padding: 10px;
       border-radius: 8px;
       font-size: 0.9rem;
   }
   .message.success 
   {
     background-color: #d4edda;
      color: #155724;
       border: 1px solid #c3e6cb;
     }

   .message.error
    { 
        background-color: #f8d7da; 
        color: #721c24;
         border: 1px solid #f5c6cb;
         }

    .back-link
     { 
        text-align: center;
         font-size: 0.9rem;
          margin-top: 20px; 
          color: #6c757d;
         }

    .back-link a 
    {
         color: #007bff;
         text-decoration: none;
         }

   @media (max-width: 768px) 
   {
   .main-container 
   {
     flex-direction: column;
      align-items: center; 
    }

   header 
   {
     flex-direction: column;
      align-items: flex-start;
     }

   nav ul 
   { 
    flex-direction: column;
     margin-top: 10px;
      gap: 10px;
     }
   }
 </style>
</head>
<body>

 <header>
 <a href="home_page.php" class="logo">MU-DORMS</a>
 <nav>
 <ul>
 <li><a href="about.php">About</a></li>
 <li><a href="dorm_type.php">Dorms</a></li>
 <li><a href="contact.php">Contact</a></li>
 </ul>
 </nav>
 <a href="create_account.php" class="sign-up-btn">Sign Up</a>
 </header>

 <div class="main-container">
 <div class="form-container">
 <h2>Reset Password</h2>
 <div id="responseMessage" class="message <?php echo $message_type; ?>" style="display: <?php echo empty($message) ? 'none' : 'block'; ?>">
   <?php echo htmlspecialchars($message); ?>
 </div>

 <?php if ($show_form): ?>
 <form id="resetPasswordForm" method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>&email=<?php echo htmlspecialchars($email); ?>">
   <b>New Password</b>
   <input type="password" id="new_password" name="new_password" placeholder="Enter the new password" required>
   <b>Confirm New Password</b>
   <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm the new password" required>
   <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($token); ?>">
   <input type="hidden" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
   <button class="btn btn-primary" type="submit">Reset Password</button>
 </form>
 <?php endif; ?>

 <div class="back-link">
   <a href="log_in_student.php">Back to Sign In.</a>
 </div>
 </div>
 </div>

 <script>
    // التحقق من تطابق كلمات المرور وطول كلمة المرور قبل الإرسال
    document.getElementById('resetPasswordForm')?.addEventListener('submit', function(event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const responseMessageDiv = document.getElementById('responseMessage');

        // تحقق من تطابق كلمات المرور قبل الإرسال
        if (newPassword !== confirmPassword) {
            event.preventDefault(); // منع الإرسال
            responseMessageDiv.style.display = 'block';
            responseMessageDiv.textContent = 'Passwords do not match.';
            responseMessageDiv.className = 'message error';
            return;
        }

        // تحقق من طول كلمة المرور قبل الإرسال
        if (newPassword.length < 8) {
            event.preventDefault(); // منع الإرسال
            responseMessageDiv.style.display = 'block';
            responseMessageDiv.textContent = 'Password must be at least 8 characters long.';
            responseMessageDiv.className = 'message error';
            return;
        }
    });
 </script>

</body>
</html>