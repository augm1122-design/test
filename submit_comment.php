<?php
header('Content-Type: application/json');

include 'conn.php';//للاتصال بقاعدة البيانات 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // يتحقق مما إذا كان حقل name موجوداً في البيانات المرسلة//trim($_POST['name']): يزيل أي مسافات بيضاء (مثل المسافات الفارغة في البداية والنهاية) من النص.
    // htmlspecialchars(): يحول الأحرف الخاصة إلى كيانات HTML لمنع هجمات XSS.
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $stars = isset($_POST['number_of_stars']) ? (int)$_POST['number_of_stars'] : 0;
    $comment_text = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';

    // هذا الشرط يتحقق من صحة المدخلات
    //يتحقق مما إذا كان حقلا الاسم والتعليق فارغين أو إذا كانت قيمة النجوم أقل من 1 أو أكبر من 5
    if (empty($name) || empty($comment_text) || $stars < 1 || $stars > 5) {
        // إذا كانت المدخلات غير صالحة، يعيد رسالة خطأ بصيغة JSON 
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields and provide a valid rating.']);
        exit;
    }
    //لإدراج البيانات في قاعدة البيانات     
    $stmt = $pdo->prepare("INSERT INTO comments (name, number_of_stars, comment) VALUES (?, ?, ?)");//علامات الاستفهام هي المدخلات التي سيتم ارسالها لاحقا
    if ($stmt === false) {// التحقق من نجاح ادراج البيانات
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $pdo->errorInfo()[2]]);
        exit;
    }
    // ربط القيم بالعلامات الاستفهامية في جملة SQL
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $stars);
    $stmt->bindParam(3, $comment_text);

    // تنفيذ الجملة والتحقق من نجاح التنفيذ
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comment added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add comment: ' . $stmt->error]);
    }
 //اغلاق جملة الادراج
    $stmt->close();
} else {//إذا لم تكن طريقة الطلب POST، يتم إرسال رسالة خطأ 
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
//اغلاق الاتصال بقاعدة البيانات
$conn->close();
?>