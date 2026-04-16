<?php
session_start();
require_once 'conn.php';

if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
    header("Location: log_in_student.php");
    exit();
}

$admin_id = $_SESSION['student_id'];
$admin_email = $_SESSION['user_email'];

// الاحصائيات
try {
    $total_students = $pdo->query("SELECT COUNT(*) FROM login WHERE level = 2")->fetchColumn();
    $total_owners = $pdo->query("SELECT COUNT(*) FROM login WHERE level = 3")->fetchColumn();
    $total_dorms = $pdo->query("SELECT COUNT(*) FROM dorms")->fetchColumn();
    $total_bookings = $pdo->query("SELECT COUNT(*) FROM booking")->fetchColumn();
    $pending_bookings = $pdo->query("SELECT COUNT(*) FROM booking WHERE status = 'Pending'")->fetchColumn();
    $total_comments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    $pending_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
    $total_revenue = $pdo->query("SELECT SUM(total_price) FROM booking WHERE status = 'Confirmed'")->fetchColumn() ?? 0;

    // جلب الملاك والطلاب
    $stmtOwners = $pdo->query("SELECT l.*, d.name as dorm_name FROM login l LEFT JOIN dorms d ON l.users_id = d.owner_id WHERE l.level = 3 ORDER BY l.users_id DESC");
    $stmtStudents = $pdo->query("SELECT * FROM login WHERE level = 2 ORDER BY users_id DESC");

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <style>
        .content-section { display: none; }
        .content-section.active { display: block; }
        .editable-td { background: #fffcf0; border: 1px dashed #ccc !important; padding: 4px; }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header"><i class="fas fa-user-shield"></i> <h2>لوحة المدير</h2></div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active" data-section="dashboard"><i class="fas fa-home"></i> الرئيسية</a>
            <a href="#" class="nav-item" data-section="dorms"><i class="fas fa-building"></i> إدارة السكنات</a>
            <a href="#" class="nav-item" data-section="owners"><i class="fas fa-users-cog"></i> إدارة الملاك</a>
            <a href="#" class="nav-item" data-section="students"><i class="fas fa-user-graduate"></i> إدارة الطلاب</a>
            <a href="#" class="nav-item" data-section="comments"><i class="fas fa-comments"></i> إدارة التعليقات</a>
            <a href="#" class="nav-item" data-section="dorm-requests"><i class="fas fa-file-signature"></i> طلبات السكنات</a>
            <a href="#" class="nav-item" data-section="messages"><i class="fas fa-envelope"></i> الرسائل</a>
            <a href="home_page.php" class="nav-item"><i class="fas fa-globe"></i> الموقع الرئيسي</a>
        </nav>
        <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> خروج</a>
    </div>

    <div class="main-content" id="mainContent">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <div class="top-bar-right">
                <div class="notifications">
    <button class="notification-btn" onclick="showAdminNotifications()">
        <i class="fas fa-bell"></i>
        </button>
</div>
                <div class="user-info"><span>مرحباً، المدير</span> <i class="fas fa-user-circle"></i></div>
            </div>
        </div>

        <div class="content-section active" id="dashboard-section">
            <div class="section-header">
                <h1 data-lang="dashboard_title">لوحة التحكم الرئيسية</h1>
                <p data-lang="dashboard_subtitle">نظرة عامة على النظام</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_students); ?></h3><p data-lang="total_students">إجمالي الطلاب</p></div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fas fa-building"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_dorms); ?></h3><p data-lang="total_dorms">إجمالي السكنات</p></div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_bookings); ?></h3><p data-lang="total_bookings">إجمالي الحجوزات</p></div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon"><i class="fas fa-users-cog"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_owners); ?></h3><p data-lang="total_owners">إجمالي الملاك</p></div>
                </div>
                <div class="stat-card cyan">
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_revenue, 2); ?> JOD</h3><p data-lang="total_revenue">إجمالي الإيرادات</p></div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="stat-details"><h3><?php echo number_format($total_comments); ?></h3><p data-lang="total_comments">إجمالي التعليقات</p></div>
                </div>
            </div>
        </div>

        <div class="content-section" id="dorms-section">
            <div class="section-header"><h1>إدارة السكنات</h1></div>
            <div class="data-table-container">
                <table id="dormsTable" class="editable-table"><thead><tr id="dormsTableHead"></tr></thead><tbody id="dormsTableBody"></tbody></table>
            </div>
        </div>

        <div class="content-section" id="owners-section">
            <div class="data-table-container">
                <h2>قائمة الملاك</h2>
                <table>
                    <thead><tr><th>ID</th><th>الاسم</th><th>البريد</th><th>الهاتف</th><th>السكن</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        <?php while($owner = $stmtOwners->fetch()) { ?>
                            <tr id="owner-row-<?= $owner['users_id'] ?>">
                                <td><?= $owner['users_id'] ?></td>
                                <td contenteditable="true" class="owner-name editable-td"><?= htmlspecialchars($owner['name']) ?></td>
                                <td><?= htmlspecialchars($owner['email']) ?></td>
                                <td contenteditable="true" class="owner-phone editable-td"><?= htmlspecialchars($owner['phone']) ?></td>
                                <td><?= htmlspecialchars($owner['dorm_name'] ?? 'لا يوجد') ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="saveUserEdit(<?= $owner['users_id'] ?>, 'owner')">حفظ</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $owner['users_id'] ?>, 'owner')">حذف</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-section" id="students-section">
            <div class="data-table-container">
                <h2>قائمة الطلاب</h2>
                <table>
                    <thead><tr><th>ID</th><th>الاسم</th><th>البريد</th><th>الهاتف</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        <?php while($student = $stmtStudents->fetch()) { ?>
                            <tr id="student-row-<?= $student['users_id'] ?>">
                                <td><?= $student['users_id'] ?></td>
                                <td contenteditable="true" class="student-name editable-td"><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td contenteditable="true" class="student-phone editable-td"><?= htmlspecialchars($student['phone']) ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="saveUserEdit(<?= $student['users_id'] ?>, 'student')">حفظ</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $student['users_id'] ?>, 'student')">حذف</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-section" id="comments-section">
            <div class="section-header"><h1>إدارة التعليقات</h1></div>
            <div class="data-table-container"><table id="commentsTable"><thead><tr><th>المعرف</th><th>الاسم</th><th>السكن</th><th>التقييم</th><th>التعليق</th><th>التاريخ</th><th>الإجراءات</th></tr></thead><tbody id="commentsTableBody"></tbody></table></div>
        </div>

        <div class="content-section" id="dorm-requests-section">
    <div class="section-header">
        <h1><i class="fas fa-file-signature"></i> مراجعة طلبات السكنات الجديدة</h1>
        <p>هنا تظهر السكنات التي سجلت حديثاً وتنتظر موافقتك لتظهر للطلاب</p>
    </div>
    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>المالك ومعلومات التواصل</th>
                    <th>اسم السكن</th>
                    <th>الموقع والبعد عن الجامعة</th>
                    <th>نوع الغرف المتاحة</th>
                    <th>تاريخ التقديم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="dormRequestsTableBody">
                <?php
                // جلب كافة التفاصيل التي تم إدخالها في عملية التسجيل
                $stmtReq = $pdo->query("SELECT d.*, l.name as owner_real_name, l.phone as owner_phone 
                                        FROM dorms d 
                                        JOIN login l ON d.owner_id = l.users_id 
                                        WHERE d.status = 'pending' 
                                        ORDER BY d.created_at DESC");
                
                while($req = $stmtReq->fetch()) { ?>
                    <tr id="req-row-<?= $req['dorm_id'] ?>">
                        <td>
                            <strong><?= htmlspecialchars($req['owner_real_name']) ?></strong><br>
                            <small><i class="fas fa-phone"></i> <?= htmlspecialchars($req['owner_phone']) ?></small><br>
                            <small><i class="fas fa-envelope"></i> <?= htmlspecialchars($req['contact_email']) ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($req['name']) ?><br>
                            <small>(<?= htmlspecialchars($req['name_ar']) ?>)</small>
                        </td>
                        <td>
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($req['location']) ?><br>
                            <small><i class="fas fa-walking"></i> <?= htmlspecialchars($req['proximity_to_university'] ?? 'غير محدد') ?></small>
                        </td>
                        <td>
                            <?php 
                                $types = explode(',', $req['room_type']);
                                foreach($types as $t) echo "<span class='badge' style='background:#e0e7ff; color:#4338ca; margin:2px; display:inline-block; padding:2px 5px; border-radius:4px;'>$t</span>";
                            ?>
                        </td>
                        <td><?= date('Y-m-d', strtotime($req['created_at'])) ?></td>
                        <td>
                            <div style="display:flex; gap:5px;">
                                <button class="btn btn-success btn-sm" onclick="handleDormRequest(<?= $req['dorm_id'] ?>, 'approve')" title="قبول ونشر السكن">
                                    <i class="fas fa-check"></i> قبول
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="handleDormRequest(<?= $req['dorm_id'] ?>, 'reject')" title="رفض الطلب">
                                    <i class="fas fa-times"></i> رفض
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                
                <?php if ($stmtReq->rowCount() == 0): ?>
                    <tr><td colspan="6" style="text-align:center; padding:20px;">لا توجد طلبات جديدة حالياً.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
        <div class="content-section" id="messages-section">
            <div class="section-header">
                <h1>نظام الرسائل</h1>
                <p>إرسال رسائل للمستخدمين</p>
            </div>

            <div class="data-table-container">
                <div class="table-header">
                    <h2>إرسال رسالة جديدة</h2>
                </div>

                <form id="messageForm" onsubmit="sendMessage(event)">
                    <div style="display: grid; gap: 20px; max-width: 800px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">نوع المستلم:</label>
                            <select id="recipientType" class="filter-select" style="width: 100%;" onchange="loadRecipients()">
                                <option value="">اختر نوع المستلم</option>
                                <option value="student">طالب</option>
                                <option value="owner">مالك</option>
                                <option value="all_students">جميع الطلاب</option>
                                <option value="all_owners">جميع الملاك</option>
                            </select>
                        </div>

                        <div id="recipientSelectDiv" style="display: none;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">المستلم:</label>
                            <select id="recipientSelect" class="filter-select" style="width: 100%;">
                                <option value="">اختر المستلم</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">الموضوع:</label>
                            <input type="text" id="messageSubject" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;" required>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">الرسالة:</label>
                            <textarea id="messageBody" rows="6" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; resize: vertical;" required></textarea>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> إرسال الرسالة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <script src="js/admin_dashboard_new.js"></script>
    <script>
document.querySelectorAll('.nav-item[data-section]').forEach(item => {
    item.addEventListener('click', function(e) {
        if (this.getAttribute('data-section') === 'messages') {
            return; 
        }

        e.preventDefault();
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        
        this.classList.add('active');
        const sectionId = this.getAttribute('data-section') + '-section';
        const target = document.getElementById(sectionId);
        if(target) target.classList.add('active');
    });
});

    async function saveUserEdit(userId, type) {
        const rowId = (type === 'owner') ? `#owner-row-${userId}` : `#student-row-${userId}`;
        const row = document.querySelector(rowId);
        const name = row.querySelector(type === 'owner' ? '.owner-name' : '.student-name').innerText.trim();
        const phone = row.querySelector(type === 'owner' ? '.owner-phone' : '.student-phone').innerText.trim();
        const apiFile = (type === 'owner') ? 'manage_owners.php' : 'manage_students.php';

        try {
            const response = await fetch(`api/admin/${apiFile}?action=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ users_id: userId, name: name, phone: phone })
            });
            const result = await response.json();
            alert(result.success ? "✅ تم التحديث بنجاح" : "❌ فشل: " + result.message);
        } catch (e) { alert("خطأ في الاتصال"); }
    }

    async function deleteUser(userId, type) {
        if (!confirm("تأكيد الحذف النهائي؟")) return;
        const apiFile = (type === 'owner') ? 'manage_owners.php' : 'manage_students.php';
        try {
            const response = await fetch(`api/admin/${apiFile}?action=delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ users_id: userId })
            });
            const result = await response.json();
            if (result.success) { alert("✅ تم الحذف"); location.reload(); }
        } catch (e) { alert("حدث خطأ"); }
    }

    async function handleDormRequest(dormId, action) {
        const response = await fetch('api/admin/handle_dorm.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ dorm_id: dormId, action: action })
        });
        const res = await response.json();
        if(res.success) { alert("تمت العملية"); location.reload(); }
    }

    async function deleteDorm(dormId) {
    if (!confirm("هل أنت متأكد من حذف هذا السكن نهائياً؟ لا يمكن التراجع عن هذا الإجراء.")) return;

    try {
        const response = await fetch(`api/admin/manage_dorms.php?action=delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ dorm_id: dormId })
        });
        
        const result = await response.json();
        if (result.success) {
            alert("✅ تم حذف السكن بنجاح");
            if (typeof loadDorms === "function") {
                loadDorms(); 
            } else {
                location.reload();
            }
        } else {
            alert("❌ فشل الحذف: " + result.message);
        }
    } catch (e) {
        alert("حدث خطأ في الاتصال بالخادم");
    }
}

function loadRecipients() {
    const type = document.getElementById('recipientType').value;
    const selectDiv = document.getElementById('recipientSelectDiv');
    const select = document.getElementById('recipientSelect');
    
    select.innerHTML = '<option value="">اختر المستلم</option>';
    
    if (type === 'student' || type === 'owner') {
        selectDiv.style.display = 'block';
        
        let users = [];
        
        if (type === 'student') {
            users = <?php 
                $stmtStudents->execute(); 
                echo json_encode($stmtStudents->fetchAll(PDO::FETCH_ASSOC)); 
            ?>;
        } else if (type === 'owner') {
            users = <?php 
                $stmtOwners->execute();
                echo json_encode($stmtOwners->fetchAll(PDO::FETCH_ASSOC)); 
            ?>;
        }
        
        users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.email;
            option.textContent = `${user.name} (${user.email})`;
            select.appendChild(option);
        });
        
    } else {
        selectDiv.style.display = 'none';
    }
}



function replyToMessage(email, type) {
    const overlay = document.getElementById('notificationsOverlay');
    if (overlay) overlay.style.display = 'none';

    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
    
    document.querySelector('.nav-item[data-section="messages"]').classList.add('active');
    document.getElementById('messages-section').classList.add('active');

    setTimeout(() => {
        const recipientType = document.getElementById('recipientType');
        if (recipientType) {
            recipientType.value = type; 
            loadRecipients(); 
            
            setTimeout(() => {
                const recipientSelect = document.getElementById('recipientSelect');
                if (recipientSelect) recipientSelect.value = email;
            }, 500);
        }
        document.getElementById('messageSubject').value = "رد على استفسارك";
        document.getElementById('messageBody').focus();
    }, 200);
}
    </script>
</body>
</html>