<?php
// يحدد أن الاستجابة ستكون بصيغة JSON
header('Content-Type: application/json');
session_start(); 

require_once 'conn.php'; 

$response = ['status' => 'error', 'message' => 'Invalid request.'];

// التحقق من أن الطلب هو POST لانها نقوم بتعديل 
// لازالة او اضافة مفضلة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // فلترة المدخلات لتجنب المشاكل الأمنية
    $dorm_id = filter_input(INPUT_POST, 'dorm_id', FILTER_VALIDATE_INT);
    $student_id = $_SESSION['student_id'] ?? null;
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); // 'add' أو 'remove'

    // التحقق من صحة المدخلات
    if (!$dorm_id || !in_array($action, ['add', 'remove'])) {
        $response['message'] = 'Missing or invalid parameters.';
        echo json_encode($response);
        exit();
    }

    // التحقق مما إذا كان المستخدم مسجل دخول
    if ($student_id === null) {
        $response['status'] = 'not_logged_in';
        $response['message'] = 'Please log in to manage your favorites.';
        echo json_encode($response);
        exit();
    }

    try {
        if ($action === 'add') {
            // التحقق أولاً مما إذا كان السكن موجودًا بالفعل في المفضلة لتجنب الإدخالات المكررة
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE student_id = ? AND dorm_id = ?");
            $stmt->execute([$student_id, $dorm_id]);
            if ($stmt->fetchColumn() > 0) {
                $response = ['status' => 'success', 'message' => 'Dorm already in favorites.', 'is_favorite' => 1];
            } else {
                // إدراج السكن في جدول المفضلة
                $stmt = $pdo->prepare("INSERT INTO favorites (student_id, dorm_id) VALUES (?, ?)");
                if ($stmt->execute([$student_id, $dorm_id])) {
                    $response = ['status' => 'success', 'message' => 'Dorm added to favorites.', 'is_favorite' => 1];
                } else {
                    $response['message'] = 'Failed to add dorm to favorites.';
                }
            }
        // حذف السكن من جدول المفضلة
        } elseif ($action === 'remove') {
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE student_id = ? AND dorm_id = ?");
            if ($stmt->execute([$student_id, $dorm_id])) {
                $response = ['status' => 'success', 'message' => 'Dorm removed from favorites.', 'is_favorite' => 0];
            } else {
                $response['message'] = 'Failed to remove dorm from favorites.';
            }
        }
    } catch (PDOException $e) {
        // التعامل مع أخطاء قاعدة البيانات
        error_log("Database error in toggle_favorite.php: " . $e->getMessage());
        $response['message'] = 'Database error: ' . $e->getMessage();
    } catch (Exception $e) {
        // في حالة وجود أي أخطاء PHP أخرى
        error_log("General error in toggle_favorite.php: " . $e->getMessage());
        $response['message'] = 'An unexpected error occurred: ' . $e->getMessage();
    }
}

// إرجاع الاستجابة بصيغة JSON
echo json_encode($response);
?>