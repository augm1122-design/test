<?php

session_start();

require_once 'conn.php';



// Check if user is logged in and is owner

if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 3) {

header("Location: log_in_student.php");

exit();

}



// Get owner ID from session - check both possible session variables

$owner_id = $_SESSION['user_id'] ?? $_SESSION['student_id'] ?? null;

$owner_email = $_SESSION['user_email'];



if (!$owner_id) {

// Try to get from email

$stmt = $pdo->prepare("SELECT users_id FROM login WHERE email = ? AND level = 3");

$stmt->execute([$owner_email]);

$result = $stmt->fetch();

if ($result) {

$owner_id = $result['users_id'];

$_SESSION['user_id'] = $owner_id;

} else {

die("خطأ: لم يتم العثور على حساب المالك");

}

}



// Get owner information

try {

$stmt = $pdo->prepare("SELECT * FROM login WHERE users_id = ?");

$stmt->execute([$owner_id]);

$owner = $stmt->fetch();



// Get owner's dorm(s)

$stmt = $pdo->prepare("SELECT * FROM dorms WHERE owner_id = ?");

$stmt->execute([$owner_id]);

$dorms = $stmt->fetchAll();



if (empty($dorms)) {

$no_dorm = true;

} else {

$no_dorm = false;

$dorm = $dorms[0]; // Get first dorm

$dorm_id = $dorm['dorm_id'];



// Get statistics

// Total rooms

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM dorm_rooms WHERE dorm_id = ?");

$stmt->execute([$dorm_id]);

$total_rooms = $stmt->fetch()['total'];



// Available rooms (rooms with capacity > current bookings)

$stmt = $pdo->prepare("

SELECT COUNT(*) as total

FROM dorm_rooms dr

WHERE dr.dorm_id = ?

AND dr.capacity > COALESCE((

SELECT COUNT(*)

FROM booking b

WHERE b.room_id = dr.room_id

AND b.status IN ('confirmed', 'pending')

), 0)

");

$stmt->execute([$dorm_id]);

$available_rooms = $stmt->fetch()['total'];



// Total bookings

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM booking WHERE dorm_id = ?");

$stmt->execute([$dorm_id]);

$total_bookings = $stmt->fetch()['total'];



// Pending bookings

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM booking WHERE dorm_id = ? AND status = 'Pending'");

$stmt->execute([$dorm_id]);

$pending_bookings = $stmt->fetch()['total'];



// Total revenue

$stmt = $pdo->prepare("SELECT SUM(total_price) as revenue FROM booking WHERE dorm_id = ? AND status = 'Confirmed'");

$stmt->execute([$dorm_id]);

$total_revenue = $stmt->fetch()['revenue'] ?? 0;



// Average rating - check if rating column exists

try {

$stmt = $pdo->query("SHOW COLUMNS FROM comments LIKE 'rating'");

$has_rating = $stmt->rowCount() > 0;



if ($has_rating) {

$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM comments WHERE dorm_id = ?");

$stmt->execute([$dorm_id]);

$avg_rating = round($stmt->fetch()['avg_rating'] ?? 0, 1);

} else {

// If no rating column, count approved comments as rating indicator

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM comments WHERE dorm_id = ? AND status = 'approved'");

$stmt->execute([$dorm_id]);

$comment_count = $stmt->fetch()['total'] ?? 0;

$avg_rating = min(5, round($comment_count / 2, 1)); // Simple calculation

}

} catch (PDOException $e) {

$avg_rating = 0;

}

}



} catch (PDOException $e) {

die("خطأ في قاعدة البيانات: " . $e->getMessage());

}

?>

<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title language-switch="owner_dashboard_title">لوحة تحكم المالك - نظام حجز السكنات</title>

<link rel="stylesheet" href="css/admin_dashboard.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<style>

.sidebar { 
    width: 220px !important; 
    background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%) !important; 
}
.main-content { 
    margin-right: 220px !important; 
    width: calc(100% - 220px) !important; 
}


.topbar { 
    border-bottom: 3px solid #1e3a8a !important; 
    background: white !important; 
    display: flex !important; 
    align-items: center !important; 
    justify-content: flex-start !important; 
    height: 70px !important;
}


.user-greeting-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-right: 20px; 
}

.sidebar-header {
    padding: 30px 10px; /* زيادة المسافة العلوية */
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header i {
    font-size: 70px !important; /* تكبير الشعار (غيري الرقم إذا بدك أكبر) */
    margin-bottom: 15px;      /* مسافة بين الشعار والنص */
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3); /* ظل خفيف ليعطي فخامة */
}

.sidebar-header h2 {
    font-size: 1.4rem; /* تكبير الخط قليلاً ليتناسب مع الشعار */
    margin: 0;
    color: white;
    font-weight: bold;
}


/* تنسيق حاوية اللغة في أعلى السايدبار */
.sidebar-lang-top {
    padding: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.1); /* خط بسيط بيفصلها عن العنوان */
    background: rgba(0,0,0,0.1); /* تمييز بسيط للخلفية */
}

.lang-btn-top {
    background: rgba(255,255,255,0.1);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 4px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    transition: 0.3s;
}

.lang-btn-top:hover {
    background: white;
    color: #1e3a8a;
}
/* 2. حاوية الاسم والجرس لضمان ظهورهما بجانب بعضهما */
.user-profile-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-direction: row;
}

/* 3. إخفاء العناصر التي تسبب تكرار الاسم في جهة اليسار */
.topbar-left, .user-info { 
    display: none !important; 
}

.topbar { 
    background: white !important; 
    border-bottom: 3px solid #1e3a8a !important; 
    height: 70px !important;
    display: flex !important;
    align-items: center !important;
    padding: 0 40px !important; /* هذه هي الإزاحة التي طلبتِها عن السايدبار */
}

.topbar h1 {
    margin: 0;
    font-size: 2rem;
    color: #1e3a8a;
    display: flex;
    gap: 10px;
}


</style>

<body>



<div class="dashboard-container">

<!-- Sidebar -->

<aside class="sidebar" id="sidebar">
   

    <div class="sidebar-header">
    <i class="fas fa-building"></i> 
    
    <h2><span language-switch="owner_panel">لوحة المالك</span></h2>
</div>

    <nav class="sidebar-nav">
        <a href="#" class="nav-item active" data-section="dashboard">
            <i class="fas fa-home"></i>
            <span language-switch="dashboard">لوحة التحكم</span>
        </a>
        <a href="#" class="nav-item" data-section="dorm">
            <i class="fas fa-building"></i>
            <span language-switch="dorm_management">إدارة السكن</span>
        </a>
        <a href="#" class="nav-item" data-section="room-prices">
            <i class="fas fa-dollar-sign"></i>
            <span language-switch="room_prices">أسعار الغرف</span>
        </a>
        <a href="#" class="nav-item" data-section="bookings">
            <i class="fas fa-calendar-check"></i>
            <span language-switch="bookings">الحجوزات</span>
        </a>
        <a href="#" class="nav-item" data-section="comments">
            <i class="fas fa-comments"></i>
            <span language-switch="comments">التعليقات</span>
        </a>
        
        <a href="logout.php" class="nav-item logout-item-custom">
            <i class="fas fa-sign-out-alt"></i>
            <span language-switch="logout">تسجيل الخروج</span>
        </a>
    </nav>
</aside>



<!-- Main Content -->

<main class="main-content">

<!-- Top Bar -->

<div class="topbar">

<div class="notification-wrapper" style="position: relative; cursor: pointer; margin-left: 20px;" onclick="showOwnerNotifications()">
    <i class="fas fa-bell" style="font-size: 22px; color: #1e3a8a;"></i>
    <span id="unreadCountBadge" style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; font-weight: bold; display: none;">0</span>
</div>
<div class="topbar-right">

<button class="menu-toggle" id="menuToggle">

<i class="fas fa-bars"></i>

</button>

<h1><span language-switch="welcome">مرحباً</span> <?php echo htmlspecialchars($owner['name']); ?></h1>

</div>



<div class="topbar-left">

<!-- Notifications Bell -->

<div class="notification-bell" style="position: relative; margin-left: 20px; cursor: pointer;" onclick="showSection('notifications')">

<i class="fas fa-bell" style="font-size: 24px; color: #1e3a8a;"></i>

<span class="notification-count" id="topbarNotificationCount" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; min-width: 18px; text-align: center; display: none;">0</span>

</div>



<div class="user-info">

<span><?php echo htmlspecialchars($owner['name']); ?></span>

</div>

</div>

</div>



<?php if ($no_dorm): ?>

<!-- No Dorm Message -->

<div class="content-section active" id="dashboard-section">

<div class="section-header">

<h2>لوحة التحكم</h2>

</div>



<div class="alert alert-warning" style="padding: 30px; text-align: center; background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px;">

<i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f59e0b; margin-bottom: 20px;"></i>

<h3 style="color: #92400e; margin-bottom: 15px;">لم يتم تعيين سكن لك بعد</h3>

<p style="color: #78350f; font-size: 16px;">

الرجاء التواصل مع الإدارة لتعيين سكن لك حتى تتمكن من استخدام لوحة التحكم.

</p>

<p style="margin-top: 20px;">

<strong>البريد الإلكتروني:</strong> admin@dormsbooking.com

</p>

</div>

</div>

<?php else: ?>

<!-- Dashboard Section -->

<div class="content-section active" id="dashboard-section">

<div class="section-header">

<h2>لوحة التحكم</h2>

<p>نظرة عامة على سكنك</p>

</div>



<!-- Statistics Cards -->

<div class="stats-grid">

<div class="stat-card blue">

<div class="stat-icon">

<i class="fas fa-door-open"></i>

</div>

<div class="stat-details">

<h3><?php echo $total_rooms; ?></h3>

<p language-switch="total_rooms">إجمالي الغرف</p>

</div>

</div>



<div class="stat-card green">

<div class="stat-icon">

<i class="fas fa-check-circle"></i>

</div>

<div class="stat-details">

<h3><?php echo $available_rooms; ?></h3>

<p language-switch="available_rooms">الغرف المتاحة</p>

</div>

</div>



<div class="stat-card orange">

<div class="stat-icon">

<i class="fas fa-calendar-alt"></i>

</div>

<div class="stat-details">

<h3><?php echo $total_bookings; ?></h3>

<p language-switch="total_bookings">إجمالي الحجوزات</p>

</div>

</div>



<div class="stat-card red">

<div class="stat-icon">

<i class="fas fa-clock"></i>

</div>

<div class="stat-details">

<h3><?php echo $pending_bookings; ?></h3>

<p language-switch="pending_bookings"> الحجوزات المعلقة</p>

</div>

</div>



<div class="stat-card purple">

<div class="stat-icon">

<i class="fas fa-dollar-sign"></i>

</div>

<div class="stat-details">

<h3><?php echo number_format($total_revenue); ?> JOD</h3>

<p language-switch="total_revenue"> إجمالي الإيرادات</p>

</div>

</div>



<div class="stat-card cyan">

<div class="stat-icon">

<i class="fas fa-star"></i>

</div>

<div class="stat-details">

<h3><?php echo $avg_rating; ?> / 5</h3>

<p language-switch="avg_rating"> متوسط التقييم</p>

</div>

</div>

</div>



<!-- Dorm Info -->

<div class="info-card" style="margin-top: 30px; padding: 25px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

<h3 style="margin-bottom: 20px; color: #1e3a8a;">

<i class="fas fa-building"></i> معلومات السكن

</h3>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">

<div>

<strong language-switch="dorm_name"> اسم السكن:</strong>

<p><?php echo htmlspecialchars($dorm['name']); ?></p>

</div>

<div>

<strong language-switch="location"> الموقع:</strong>

<p><?php echo htmlspecialchars($dorm['location']); ?></p>

</div>

<div>

<strong language-switch="gender"> النوع:</strong>

<p><?php echo $dorm['gender'] === 'male' ? 'ذكور' : 'إناث'; ?></p>

</div>

<div>

<strong language-switch="status"> الحالة:</strong>

<p><span class="status-badge <?php echo $dorm['status'] ?? 'active'; ?>">

<?php echo ($dorm['status'] ?? 'active') === 'active' ? 'نشط' : 'غير نشط'; ?>

</span></p>

</div>

</div>



<!-- Amenities Section -->

<div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">

<h4 style="margin-bottom: 15px; color: #1e3a8a;">

<i class="fas fa-tools"></i> المرافق المتاحة

</h4>

<div id="dashboardAmenitiesContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">

<!-- Will be filled by JavaScript -->

<p style="color: #6b7280;" language-switch="loading">جاري التحميل...</p>

</div>

</div>

</div>

</div>



<!-- Dorm Management Section -->

<div class="content-section" id="dorm-section">

<div class="section-header">

<h2 language-switch="dorm_management">إدارة السكن</h2>

<p language-switch="dorm_management_desc">تعديل معلومات السكن الخاص بك</p>

</div>



<!-- Dorm Information Table -->

<div class="table-container" style="margin-bottom: 30px;">

<h3 language-switch="dorm_info">معلومات السكن</h3>

<table id="dormInfoTable">

<thead>

<tr>

<th language-switch="field">الحقل</th>

<th language-switch="value">القيمة</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="dormInfoTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

<button class="btn btn-success" onclick="saveDormInfo()" style="margin-top: 15px;">

<i class="fas fa-save"></i> <span language-switch="save_all_changes">حفظ جميع التغييرات</span>

</button>

</div>



<!-- Facilities Table -->

<div class="table-container">

<h3 language-switch="facilities">المرافق والخدمات</h3>

<table id="facilitiesTable">

<thead>

<tr>

<th language-switch="facility_name">اسم المرفق</th>

<th language-switch="available">متوفر</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="facilitiesTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

<button class="btn btn-success" onclick="saveFacilities()" style="margin-top: 15px;">

<i class="fas fa-save"></i> <span language-switch="save_facilities">حفظ المرافق</span>

</button>

</div>

</div>



<!-- Room Prices Section -->

<div class="content-section" id="room-prices-section">

<div class="section-header">

<h2 language-switch="room_prices">أسعار الغرف</h2>

<p language-switch="room_prices_desc">عرض وتعديل معلومات الغرف وأسعارها والطلاب المحجوزين</p>

</div>



<div class="table-container">

<table id="roomPricesTable">

<thead>

<tr>

<th language-switch="room_number">رقم الغرفة</th>

<th language-switch="room_type">نوع الغرفة</th>

<th language-switch="monthly_rent">الإيجار الشهري (دينار)</th>

<th language-switch="booked_by">محجوزة من قبل</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="roomPricesTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

</div>

</div>



<!-- Bookings Section -->

<div class="content-section" id="bookings-section">

<div class="section-header">

<h2 language-switch="bookings_management">إدارة الحجوزات</h2>

<p language-switch="bookings_management_desc">عرض وإدارة جميع الحجوزات</p>

</div>



<div class="filter-bar">

<input type="text" id="bookingSearch" placeholder="بحث..." onkeyup="filterBookings()">

<select id="bookingStatusFilter" onchange="filterBookings()">

<option value="" language-switch="all_statuses">جميع الحالات</option>

<option value="Pending" language-switch="pending">معلق</option>

<option value="Confirmed" language-switch="confirmed">مؤكد</option>

<option value="Cancelled" language-switch="cancelled">ملغي</option>

</select>

</div>



<div class="table-container">

<table>

<thead>

<tr>

<th language-switch="booking_id">رقم الحجز</th>

<th language-switch="student_name">اسم الطالب</th>

<th language-switch="student_email">البريد الإلكتروني</th>

<th language-switch="student_phone">رقم الهاتف</th>

<th language-switch="room_type">نوع الغرفة</th>

<th language-switch="booking_date">تاريخ الحجز</th>

<th language-switch="amount">المبلغ</th>

<th language-switch="status">الحالة</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="bookingsTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

</div>

</div>



<!-- Comments Section -->

<div class="content-section" id="comments-section">

<div class="section-header">

<h2 language-switch="comments_management">إدارة التعليقات</h2>

<p language-switch="comments_management_desc">إدارة تعليقات الطلاب على السكن</p>

</div>




<div class="filter-bar">

<select id="commentRatingFilter" onchange="filterComments()">

<option value="" language-switch="all_ratings">جميع التقييمات</option>

<option value="5">5 <span language-switch="stars">نجوم</span></option>

<option value="4">4 <span language-switch="stars">نجوم</span></option>

<option value="3">3 <span language-switch="stars">نجوم</span></option>

<option value="2">2 <span language-switch="stars">نجوم</span></option>

<option value="1">1 <span language-switch="star">نجمة</span></option>

</select>

</div>



<div class="table-container">

<table>

<thead>

<tr>

<th language-switch="commenter_name">اسم المعلق</th>

<th language-switch="rating">التقييم</th>

<th language-switch="comment">التعليق</th>

<th language-switch="date">التاريخ</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="commentsTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

</div>

</div>



<!-- Notifications Section -->

<div class="content-section" id="notifications-section">

<div class="section-header">

<h2 language-switch="notifications">الإشعارات</h2>

<p language-switch="notifications_desc">عرض الرسائل والإشعارات</p>

</div>



<div class="table-container">

<table>

<thead>

<tr>

<th language-switch="sender">المرسل</th>

<th language-switch="subject">الموضوع</th>

<th language-switch="message">الرسالة</th>

<th language-switch="date">التاريخ</th>

<th language-switch="actions">الإجراءات</th>

</tr>

</thead>

<tbody id="notificationsTableBody">

<!-- Will be filled by JavaScript -->

</tbody>

</table>

</div>

</div>

<?php endif; ?>

</main>

</div>



<script>

const dormId = <?php echo $dorm_id ?? 0; ?>;

const ownerId = <?php echo $owner_id; ?>;

</script>





<div class="modal fade" id="occupantsModal" tabindex="-1" aria-hidden="true">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header bg-info text-white">

<h5 class="modal-title" id="occupantsModalTitle">قائمة الطلاب</h5>

<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<div class="modal-body" id="occupantsModalBody" style="white-space: pre-wrap; line-height: 1.8;">

</div>

<div class="modal-footer">

<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>

</div>

</div>

</div>

</div>

<script src="js/language.js"></script>

<script src="js/owner_dashboard.js"></script>

</body>

</html>