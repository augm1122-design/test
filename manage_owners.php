<?php
session_start();
require_once '../../conn.php';

header('Content-Type: application/json; charset=utf-8');

// التحقق من أن المستخدم هو أدمن
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك بالوصول']);
    exit();
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_all':
            // جلب جميع الملاك مع أسماء سكناتهم
            $sql = "SELECT l.*, d.name as dorm_name, d.dorm_id
                    FROM login l
                    LEFT JOIN dorms d ON l.users_id = d.owner_id
                    WHERE l.level = 3
                    ORDER BY l.users_id DESC";
            $stmt = $pdo->query($sql);
            $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $owners
            ]);
            break;
            
        case 'update':
            // تحديث بيانات المالك
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['users_id'])) {
                echo json_encode(['success' => false, 'message' => 'معرف المالك مطلوب']);
                exit();
            }
            
            $users_id = $input['users_id'];
            
            // بناء استعلام التحديث ديناميكياً بناءً على الحقول المرسلة (الاسم والهاتف)
            $set_parts = [];
            $params = [];
            
            foreach ($input as $key => $value) {
                // نستبعد المفاتيح التي لا يجب تحديثها أو التي ليست أعمدة في جدول login
                if ($key !== 'users_id' && $key !== 'level' && $key !== 'dorm_name' && $key !== 'dorm_id') {
                    $set_parts[] = "`$key` = ?";
                    $params[] = $value;
                }
            }
            
            if (empty($set_parts)) {
                echo json_encode(['success' => false, 'message' => 'لا توجد بيانات لتحديثها']);
                exit();
            }

            $params[] = $users_id;
            $sql = "UPDATE login SET " . implode(', ', $set_parts) . " WHERE users_id = ? AND level = 3";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode([
                'success' => true,
                'message' => 'تم تحديث بيانات المالك بنجاح'
            ]);
            break;

        case 'delete':
            // حذف المالك
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['users_id'])) {
                echo json_encode(['success' => false, 'message' => 'معرف المستخدم مطلوب للحذف']);
                exit();
            }

            // ملاحظة: سيتم حذف السكنات المرتبطة تلقائياً إذا كان هناك Foreign Key مع ON DELETE CASCADE
            $stmt = $pdo->prepare("DELETE FROM login WHERE users_id = ? AND level = 3");
            $stmt->execute([$input['users_id']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'تم حذف حساب المالك بنجاح'
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'الإجراء المطلوب غير مدعوم']);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>