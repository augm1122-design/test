<?php
// للتصحيح فقط  بقدر اشيلها في بيئة الإنتاج
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // مهم جدا لبدء الجلسة في بداية الملف لتخزين معلومات المستخدم

//  ملف اتصال قاعدة البيانات
require_once 'conn.php';

header('Content-Type: application/json');

// النموذج تم إرساله
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جلب البريد الإلكتروني وكلمة المرور من طلب POST
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // تنقية البريد الإلكتروني من اي حروف لا يجب ان تكون ضمن البريد
    $password = $_POST['password'] ?? '';// جلب كلمة المرور واذا لم تكن موجودة تعيينها كسلسلة فارغة
    // 'status': يُعبر عن حالة العملية (نجاح أو خطأ).
    // 'message': يحتوي على رسالة توضيحية للمستخدم.
    $response = ['status' => 'error', 'message' => '']; // تهيئة مصفوفة الاستجابة لانشاء json

    // التحقق من أن الحقول ليست فارغة
    if (empty($email) || empty($password)) {
        $response['message'] = 'الرجاء إدخال بريدك الإلكتروني وكلمة المرور';
    } else {
        try {// للتعامل مع أخطاء قاعدة البيانات
            //بدلاً من وضع المتغير مباشرةً، يتم استخدام علامة استفهام (?) كبديل
            $stmt = $pdo->prepare("SELECT users_id, email, password, level FROM login WHERE email = ?");//pdoهو كائن اتصال قاعدة البيانات الذي تم إنشاؤه في ملف conn.php
            $stmt->execute([$email]);//ينفذ الاستعلام، ويقوم بإدخال قيمة $email في مكان علامة الاستفهام
        //     يجلب الصف الأول من نتائج الاستعلام ويخزنه في المتغير $user كمصفوفة ارتباطية (keys and values)
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {// إذا تم العثور على المستخدم
                // التحقق من كلمة المرور باستخدام password_verify (دالة آمنة للمقارنة مع الهاش)
                if (password_verify($password, $user['password'])) {
                     
                    // التحقق من مستوى المستخدم
                    $user_level = $user['level'];
                     if ($user_level == 2) {
                    // كلمة المرور صحيحة
                    $response['status'] = 'success';
                    $response['message'] = 'login successful';
                    $response['redirect'] = 'dorm_type.php'; // الوجهة بعد تسجيل الدخول بنجاح

                    //التعديل الأساسي إعداد متغيرات الجلسة
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['student_id'] = $user['users_id']; // هذا هو معرف المستخدم الذي سنستخدمه للمفضلة
                    $_SESSION['user_id'] = $user['users_id']; // نفس المعرف لتوحيد الاستخدام
                    $_SESSION['user_level'] = $user_level; // تخزين مستوى المستخدم إذا كنت تستخدمه

                    } elseif ($user_level == 1) {
                        // كلمة المرور صحيحة
                        $response['status'] = 'success';
                        $response['message'] = 'login successful';
                        $response['redirect'] = 'admin_dashboard.php'; // الوجهة بعد تسجيل الدخول بنجاح

                        //التعديل الأساسي إعداد متغيرات الجلسة
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['student_id'] = $user['users_id']; // هذا هو معرف المستخدم الذي سنستخدمه للمفضلة
                        $_SESSION['user_id'] = $user['users_id']; // نفس المعرف لتوحيد الاستخدام
                        $_SESSION['user_level'] = $user_level; // تخزين مستوى المستخدم إذا كنت تستخدمه

                     } elseif ($user_level == 3) {
                        // كلمة المرور صحيحة
                        $response['status'] = 'success';
                        $response['message'] = 'login successful';
                        $response['redirect'] = 'owner_dashboard.php'; // الوجهة بعد تسجيل الدخول بنجاح

                        //التعديل الأساسي إعداد متغيرات الجلسة
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['student_id'] = $user['users_id']; // هذا هو معرف المستخدم الذي سنستخدمه للمفضلة
                        $_SESSION['user_id'] = $user['users_id']; // نفس المعرف لتوحيد الاستخدام
                        $_SESSION['user_level'] = $user_level; // تخزين مستوى المستخدم إذا كنت تستخدمه
                     } else {
                        // مستوى المستخدم غير معروف
                        $response['message'] = 'Unknown user level.';
                     }  


                } else {
                    // كلمة المرور غير صحيحة
                    $response['message'] = 'email or password is incorrect.';
                }
            } else {
                // المستخدم غير موجود
                $response['message'] = 'email or password is incorrect.';
            }
        } catch (PDOException $e) {
            // التعامل مع أخطاء قاعدة البيانات
            $response['message'] = 'Database error: ' . $e->getMessage();
        }
    }
    echo json_encode($response); // إرجاع الاستجابة كـ JSON
    exit(); // إنهاء السكريبت
} else {
    // إذا لم يكن الطلب POST (مثلاً، إذا حاول أحدهم الوصول إلى هذا الملف مباشرةً)
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'الوصول غير مسموح به.']);
    exit();
}
?>