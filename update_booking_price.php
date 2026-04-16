<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json');

// Check if user is owner
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;

    if (!$owner_id) {
        echo json_encode(['success' => false, 'message' => 'لم يتم العثور على معرف المالك']);
        exit();
    }

    if (empty($input['booking_id']) || !isset($input['total_price'])) {
        echo json_encode(['success' => false, 'message' => 'معرف الحجز والمبلغ مطلوبان']);
        exit();
    }

    $booking_id = $input['booking_id'];
    $total_price = floatval($input['total_price']);

    if ($total_price < 0) {
        echo json_encode(['success' => false, 'message' => 'المبلغ يجب أن يكون أكبر من أو يساوي صفر']);
        exit();
    }

    // Get booking details and verify owner owns the dorm
    $stmt = $pdo->prepare("SELECT b.*, d.owner_id
                           FROM booking b
                           JOIN dorms d ON b.dorm_id = d.dorm_id
                           WHERE b.booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();

    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'الحجز غير موجود']);
        exit();
    }

    if ($booking['owner_id'] != $owner_id) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح لتعديل هذا الحجز']);
        exit();
    }

    // Update booking total_price
    $sql = "UPDATE booking SET total_price = :total_price WHERE booking_id = :booking_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':total_price' => $total_price,
        ':booking_id' => $booking_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث المبلغ بنجاح'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

