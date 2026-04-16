<?php
include 'conn.php';

if(isset($_POST['register_owner'])) {
    try {
        // بدء عملية الـ Transaction لضمان حفظ كل الجداول أو التراجع في حال حدوث خطأ
        $pdo->beginTransaction(); 

        // 1. إنشاء حساب المالك في جدول login (Level 3)
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql_user = "INSERT INTO login (name, email, password, phone, level) VALUES (?, ?, ?, ?, 3)";
        $pdo->prepare($sql_user)->execute([$_POST['name'], $_POST['email'], $password, $_POST['phone']]);
        $owner_id = $pdo->lastInsertId();

        // 2. معالجة أنواع الغرف النصية لجدول dorms
        $room_types = [];
        if(!empty($_POST['price_single']) && $_POST['price_single'] > 0) $room_types[] = "Single";
        if(!empty($_POST['price_double']) && $_POST['price_double'] > 0) $room_types[] = "Double";
        if(!empty($_POST['price_triple']) && $_POST['price_triple'] > 0) $room_types[] = "Triple";
        $room_types_str = implode(',', $room_types);

        // 3. إدخال بيانات السكن الأساسية (الحالة الافتراضية pending)
        $sql_dorm = "INSERT INTO dorms (name, name_ar, location, gender, description, image_url, owner_id, owner_name, contact_email, contact_phone, proximity_to_university, room_type, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $pdo->prepare($sql_dorm)->execute([
            $_POST['dorm_name'], $_POST['name_ar'], $_POST['location'], $_POST['gender'], 
            $_POST['description'], $_POST['image_url'], $owner_id, $_POST['name'], 
            $_POST['email'], $_POST['phone'], $_POST['proximity'], $room_types_str
        ]);
        $dorm_id = $pdo->lastInsertId();

        // 4. إنشاء طابق افتراضي للسكن (Ground Floor) لتمكين إضافة الغرف
        $sql_floor = "INSERT INTO dorm_floors (dorm_id, floor_number, floor_name, total_rooms) VALUES (?, 0, 'Ground Floor', 3)";
        $pdo->prepare($sql_floor)->execute([$dorm_id]);
        $floor_id = $pdo->lastInsertId();

        // 5. تعبئة الأسعار (dorm_room_prices) وإنشاء غرف نموذجية (dorm_rooms)
        $prices_map = [
            'Single' => $_POST['price_single'],
            'Double' => $_POST['price_double'],
            'Triple' => $_POST['price_triple']
        ];

        $sql_ins_price = "INSERT INTO dorm_room_prices (dorm_id, room_type, price) VALUES (?, ?, ?)";
        $stmt_ins_price = $pdo->prepare($sql_ins_price);

        $sql_ins_room = "INSERT INTO dorm_rooms (floor_id, dorm_id, room_number, room_type, capacity, available_spots, price_per_month, is_available) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt_ins_room = $pdo->prepare($sql_ins_room);

        foreach ($prices_map as $type => $price) {
            if(!empty($price) && $price > 0) {
                // حفظ السعر في جدول الأسعار
                $stmt_ins_price->execute([$dorm_id, $type, $price]);

                // إنشاء غرفة افتراضية واحدة لكل نوع تم إدخال سعره (رقم الغرفة عشوائي للتمثيل)
                $room_no = ($type[0]) . "0" . rand(1, 9); 
                $capacity = ($type == 'Single') ? 1 : (($type == 'Double') ? 2 : 3);
                
                $stmt_ins_room->execute([$floor_id, $dorm_id, $room_no, $type, $capacity, $capacity, $price]);
            }
        }

        // 6. إدخال المرافق (dorm_amenities)
        if(!empty($_POST['amenities'])) {
            $stmt_amenity = $pdo->prepare("INSERT INTO dorm_amenities (dorm_id, amenity_id) VALUES (?, ?)");
            foreach($_POST['amenities'] as $id) {
                $stmt_amenity->execute([$dorm_id, $id]);
            }
        }

        // تنفيذ كل العمليات أعلاه
        $pdo->commit(); 
        echo "<script>alert('Application Submitted Successfully! Your dorm is now under review by Admin.'); window.location.href='log_in_student.php';</script>";

    } catch (Exception $e) {
        // في حال حدوث أي خطأ، يتم إلغاء كل العمليات السابقة (لا يُنشأ حساب ولا سكن)
        $pdo->rollBack(); 
        die("Registration Error: " . $e->getMessage());
    }
}
?>