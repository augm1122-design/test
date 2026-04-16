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
    $owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'];

    if (empty($input['dorm_id'])) {
        echo json_encode(['success' => false, 'message' => 'معرف السكن مطلوب']);
        exit();
    }

    $dorm_id = $input['dorm_id'];

    // Verify owner owns this dorm
    $stmt = $pdo->prepare("SELECT dorm_id FROM dorms WHERE dorm_id = ? AND owner_id = ?");
    $stmt->execute([$dorm_id, $owner_id]);

    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'غير مصرح لتعديل هذا السكن']);
        exit();
    }

    // Check if updating single field or multiple fields
    if (isset($input['field']) && isset($input['value'])) {
        // Update single field
        $field = $input['field'];
        $value = $input['value'];

        // جميع الحقول المسموح بتعديلها
        $allowedFields = [
            'name', 'location', 'description', 'room_type',
            'price_range', 'gender', 'image', 'status'
        ];

        if (!in_array($field, $allowedFields)) {
            echo json_encode(['success' => false, 'message' => 'حقل غير مسموح بتعديله']);
            exit();
        }

        $sql = "UPDATE dorms SET $field = ?, updated_at = NOW() WHERE dorm_id = ? AND owner_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value, $dorm_id, $owner_id]);

    } else if (isset($input['updates'])) {
        // Update multiple fields
        $updates = $input['updates'];
        $setClauses = [];
        $values = [];

        // جميع الحقول المسموح بتعديلها
        $allowedFields = [
            'name', 'location', 'description', 'room_type',
            'price_range', 'gender', 'image', 'status'
        ];

        foreach ($updates as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $setClauses[] = "$field = ?";
                $values[] = $value;
            }
        }

        if (empty($setClauses)) {
            echo json_encode(['success' => false, 'message' => 'لا توجد حقول للتحديث']);
            exit();
        }

        $setClauses[] = "updated_at = NOW()";
        $values[] = $dorm_id;
        $values[] = $owner_id;

        $sql = "UPDATE dorms SET " . implode(', ', $setClauses) . " WHERE dorm_id = ? AND owner_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

    } else {
        // Legacy format - update all fields
        $sql = "UPDATE dorms SET
                name = :name,
                location = :location,
                description = :description,
                price_range = :price_range,
                updated_at = NOW()
                WHERE dorm_id = :dorm_id AND owner_id = :owner_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $input['name'],
            ':location' => $input['location'],
            ':description' => $input['description'] ?? '',
            ':price_range' => $input['price_range'] ?? '',
            ':dorm_id' => $dorm_id,
            ':owner_id' => $owner_id
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث معلومات السكن بنجاح'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>

