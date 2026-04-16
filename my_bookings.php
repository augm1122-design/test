<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: log_in_student.php");
    exit();
}

try {
    $email = $_SESSION['user_email'];

    // جلب حجوزات المستخدم من جدول booking باستخدام JOIN للحصول على اسم السكن
    $sql = "SELECT b.booking_id, d.name AS dorm_name, b.room_type, b.status, b.booking_date, b.total_price
            FROM booking AS b
            JOIN dorms AS d ON b.dorm_id = d.dorm_id
            WHERE b.email = ?
            ORDER BY b.booking_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("خطأ في قاعدة البيانات: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجوزاتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .bookings-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 700px;
            text-align: right;
        }
        .bookings-container h2 {
            text-align: center;
            color: #333;
        }
        .bookings-list {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-accepted {
            color: #28a745; /* أخضر */
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545; /* أحمر */
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107; /* أصفر */
            font-weight: bold;
        }
        .no-bookings {
            text-align: center;
            color: #666;
            margin-top: 50px;
        }
        .back-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #6c757d;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="bookings-container">
        <h2>حجوزاتي</h2>
        <?php if (!empty($bookings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>اسم السكن</th>
                        <th>نوع الغرفة</th>
                        <th>تاريخ الحجز</th>
                        <th>المبلغ (JOD)</th>
                        <th>حالة الحجز</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr id="booking-<?php echo $booking['booking_id']; ?>">
                            <td><?php echo htmlspecialchars($booking['dorm_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td><?php echo number_format($booking['total_price'] ?? 0, 2); ?></td>
                            <td>
                                <?php
                                $status = strtolower($booking['status']);
                                if ($status == 'confirmed'): ?>
                                    <span class="status-accepted">مؤكد</span>
                                <?php elseif ($status == 'cancelled'): ?>
                                    <span class="status-rejected">ملغي</span>
                                <?php else: ?>
                                    <span class="status-pending">معلق</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($status == 'pending' || $status == 'confirmed'): ?>
                                    <button class="cancel-btn" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                                        <i class="fas fa-times"></i> إلغاء
                                    </button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-bookings">لا توجد حجوزات حتى الآن.</p>
        <?php endif; ?>
        <a href="user_profile.php" class="back-button">العودة إلى الملف الشخصي</a>
    </div>

    <script>
    async function cancelBooking(bookingId) {
        if (!confirm('هل أنت متأكد من إلغاء هذا الحجز؟')) {
            return;
        }

        try {
            const response = await fetch('api/student/cancel_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ booking_id: bookingId })
            });

            const data = await response.json();

            if (data.success) {
                alert('تم إلغاء الحجز بنجاح');
                // Remove the row from table
                const row = document.getElementById('booking-' + bookingId);
                if (row) {
                    row.remove();
                }
                // Reload page if no bookings left
                const tbody = document.querySelector('tbody');
                if (tbody && tbody.children.length === 0) {
                    location.reload();
                }
            } else {
                alert('خطأ: ' + (data.message || 'فشل إلغاء الحجز'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إلغاء الحجز');
        }
    }
    </script>
</body>
</html>