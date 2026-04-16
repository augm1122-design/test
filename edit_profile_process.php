<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: log_in_student.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_email = $_SESSION['user_email'];

    // استلام البيانات من النموذج
    $name = $_POST['name'] ?? '';
    $national_id = $_POST['national_id'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $new_email = $_POST['email'] ?? '';
    $specialty = $_POST['specialty'] ?? '';

    // التحقق من صحة البيانات
    if (empty($name) || empty($national_id) || empty($phone) || empty($specialty) || empty($new_email) || empty($gender)) {
        header("Location: edit_profile.php?message=" . urlencode("الرجاء تعبئة جميع الحقول.") . "&type=error");
        exit();
    }

    try {
        // التحقق من أن البريد الإلكتروني الجديد غير مستخدم من قبل مستخدم آخر
        if ($current_email !== $new_email) {
            $check_email_sql = "SELECT email FROM login WHERE email = ?";
            $check_email_stmt = $pdo->prepare($check_email_sql);
            $check_email_stmt->execute([$new_email]);
            if ($check_email_stmt->rowCount() > 0) {
                header("Location: edit_profile.php?message=" . urlencode("البريد الإلكتروني هذا مستخدم بالفعل.") . "&type=error");
                exit();
            }
        }

        // تحديث البيانات في جدول login
        $sql = "UPDATE login SET name = ?, national_id = ?, gender = ?, phone = ?, email = ?, specialty = ? WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $national_id, $gender, $phone, $new_email, $specialty, $current_email]);

        // تحديث الجلسة بالبريد الإلكتروني الجديد إذا تم تغييره
        $_SESSION['user_email'] = $new_email;

        // إعادة التوجيه إلى صفحة الملف الشخصي مع رسالة نجاح
        header("Location: user_profile.php?message=" . urlencode("تم تحديث معلوماتك بنجاح.") . "&type=success");
        exit();

    } catch (PDOException $e) {
        // إعادة التوجيه إلى صفحة التعديل مع رسالة خطأ
        header("Location: edit_profile.php?message=" . urlencode("خطأ في قاعدة البيانات: " . $e->getMessage()) . "&type=error");
        exit();
    }
} else {
    // إذا لم يكن الطلب POST، أعد التوجيه إلى صفحة الملف الشخصي
    header("Location: user_profile.php");
    exit();
}