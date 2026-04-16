// ============================================
// Admin Dashboard JavaScript
// ============================================

// Global variables
let currentSection = 'dashboard';
let allDorms = [];
let allOwners = [];
let allStudents = [];
let allBookings = [];
let allComments = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    loadDashboardData();
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});

// Initialize dashboard
function initializeDashboard() {
    // Setup navigation
    setupNavigation();

    // Setup menu toggle
    setupMenuToggle();

    // Load initial data
    loadDorms();
    loadOwners();
    loadStudents();
    loadBookings();
    loadComments();

    // Setup form handlers
    setupFormHandlers();
}

// Setup navigation
function setupNavigation() {
    const navItems = document.querySelectorAll('.nav-item[data-section]');

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            showSection(section);
        });
    });
}

// Show section
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });

    // Show selected section
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
    }

    // Update navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    const activeNav = document.querySelector(`.nav-item[data-section="${sectionName}"]`);
    if (activeNav) {
        activeNav.classList.add('active');
    }

    currentSection = sectionName;

    // Reload data for the section
    reloadSectionData(sectionName);
}

// Reload section data
function reloadSectionData(sectionName) {
    switch(sectionName) {
        case 'dorms':
            loadDorms();
            break;
        case 'owners':
            loadOwners();
            break;
        case 'students':
            loadStudents();
            break;
        case 'bookings':
            loadBookings();
            break;
        case 'comments':
            loadComments();
            break;
    }
}

// Setup menu toggle for mobile
function setupMenuToggle() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
}

// Load dashboard data
function loadDashboardData() {
    console.log('Dashboard initialized successfully');
}

// Load notifications
function loadNotifications() {
    fetch('api/admin/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationBadge(data.unread_count + (data.system_notifications ? data.system_notifications.length : 0));
                window.adminNotifications = data;
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Toggle notifications panel
function toggleNotificationsPanel() {
    const panel = document.getElementById('notificationsPanel');
    if (panel.style.display === 'none' || panel.style.display === '') {
        panel.style.display = 'block';
        displayNotificationsInPanel();
    } else {
        panel.style.display = 'none';
    }
}

// Close notifications panel
function closeNotificationsPanel() {
    const panel = document.getElementById('notificationsPanel');
    panel.style.display = 'none';
}

// Display notifications in panel
function displayNotificationsInPanel() {
    const body = document.getElementById('notificationsBody');

    if (!window.adminNotifications) {
        body.innerHTML = '<p style="text-align: center; padding: 20px;">جاري التحميل...</p>';
        loadNotifications();
        setTimeout(displayNotificationsInPanel, 500);
        return;
    }

    const data = window.adminNotifications;
    let html = '';

    // Display system notifications
    if (data.system_notifications && data.system_notifications.length > 0) {
        data.system_notifications.forEach(notif => {
            html += `
                <div class="notification-item">
                    <div class="notification-sender">النظام</div>
                    <div class="notification-message">${notif.message}</div>
                    <div class="notification-time">الآن</div>
                </div>
            `;
        });
    }

    // Display user notifications
    if (data.notifications && data.notifications.length > 0) {
        data.notifications.forEach(notif => {
            const unreadClass = notif.is_read == 0 ? 'unread' : '';
            const senderEmail = notif.sender_email ? `<div class="notification-email">من: ${notif.sender_email}</div>` : '';
            html += `
                <div class="notification-item ${unreadClass}">
                    <div class="notification-sender">${notif.title || 'إشعار'}</div>
                    ${senderEmail}
                    <div class="notification-message">${notif.message}</div>
                    <div class="notification-time">${notif.created_at || ''}</div>
                </div>
            `;
        });
    }

    if (html === '') {
        html = '<p style="text-align: center; padding: 20px; color: #6b7280;">لا توجد إشعارات</p>';
    }

    body.innerHTML = html;
}

// Close panel when clicking outside
document.addEventListener('click', function(event) {
    const panel = document.getElementById('notificationsPanel');
    const btn = document.querySelector('.notification-btn');

    if (panel && btn && !panel.contains(event.target) && !btn.contains(event.target)) {
        panel.style.display = 'none';
    }
});

// ============================================
// Dorms Management
// ============================================

function loadDorms() {
    fetch('api/admin/get_dorms.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allDorms = data.dorms;
                displayDorms(data.dorms);
            } else {
                showAlert('خطأ في تحميل السكنات: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error loading dorms:', error);
            showAlert('خطأ في الاتصال بالخادم', 'error');
        });
}

function displayDorms(dorms) {
    const tbody = document.getElementById('dormsTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    dorms.forEach(dorm => {
        const row = `
            <tr>
                <td>${dorm.dorm_id}</td>
                <td>${dorm.name}</td>
                <td>${dorm.location}</td>
                <td>${dorm.gender === 'male' ? 'ذكور' : 'إناث'}</td>
                <td>${dorm.owner_name || 'غير محدد'}</td>
                <td><span class="status-badge ${dorm.status}">${getStatusText(dorm.status)}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewDorm(${dorm.dorm_id})" title="عرض">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editDorm(${dorm.dorm_id})" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" style="background: #10b981;" onclick="manageDormAmenities(${dorm.dorm_id}, '${dorm.name}')" title="إدارة المرافق">
                            <i class="fas fa-tools"></i>
                        </button>
                        <button class="action-btn delete" onclick="deleteDorm(${dorm.dorm_id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterDorms() {
    const searchTerm = document.getElementById('dormSearch').value.toLowerCase();
    const genderFilter = document.getElementById('genderFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#dormsTableBody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const gender = row.cells[3].textContent;
        const status = row.querySelector('.status-badge').className;
        
        let showRow = true;
        
        if (searchTerm && !name.includes(searchTerm)) {
            showRow = false;
        }
        
        if (genderFilter && !gender.includes(genderFilter === 'male' ? 'ذكور' : 'إناث')) {
            showRow = false;
        }
        
        if (statusFilter && !status.includes(statusFilter)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function openAddDormModal() {
    // This would open a modal to add a new dorm
    alert('فتح نافذة إضافة سكن جديد');
}

function viewDorm(dormId) {
    window.location.href = `dorm_details.php?dorm_id=${dormId}`;
}

function editDorm(dormId) {
    alert('تعديل السكن رقم: ' + dormId);
}

function deleteDorm(dormId) {
    if (confirm('هل أنت متأكد من حذف هذا السكن؟')) {
        fetch('api/admin/delete_dorm.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ dorm_id: dormId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف السكن بنجاح');
                loadDorms();
            } else {
                alert('فشل حذف السكن: ' + data.message);
            }
        });
    }
}

// ============================================
// Owners Management
// ============================================

function loadOwners() {
    fetch('api/admin/get_owners.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allOwners = data.owners;
                displayOwners(data.owners);
            } else {
                console.error('Error loading owners:', data.message);
            }
        })
        .catch(error => console.error('Error loading owners:', error));
}

function displayOwners(owners) {
    const tbody = document.getElementById('ownersTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (owners.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">لا توجد بيانات</td></tr>';
        return;
    }

    owners.forEach(owner => {
        const row = `
            <tr>
                <td>${owner.users_id}</td>
                <td>${owner.name}</td>
                <td>${owner.email}</td>
                <td>${owner.phone || 'غير محدد'}</td>
                <td>${owner.dorm_count || 0}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewOwner(${owner.users_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editOwner(${owner.users_id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function viewOwner(ownerId) {
    const owner = allOwners.find(o => o.users_id == ownerId);
    if (owner) {
        alert(`معلومات المالك:\nالاسم: ${owner.name}\nالبريد: ${owner.email}\nالهاتف: ${owner.phone || 'غير محدد'}\nعدد السكنات: ${owner.dorm_count || 0}`);
    }
}

function editOwner(ownerId) {
    alert('ميزة التعديل قيد التطوير');
}

// ============================================
// Students Management
// ============================================

function loadStudents() {
    fetch('api/admin/get_students.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allStudents = data.students;
                displayStudents(data.students);
            } else {
                console.error('Error loading students:', data.message);
            }
        })
        .catch(error => console.error('Error loading students:', error));
}

function displayStudents(students) {
    const tbody = document.getElementById('studentsTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">لا توجد بيانات</td></tr>';
        return;
    }

    students.forEach(student => {
        const row = `
            <tr>
                <td>${student.users_id}</td>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.phone || 'غير محدد'}</td>
                <td>${student.university || 'غير محدد'}</td>
                <td>${student.bookings_count || 0}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" onclick="viewStudent(${student.users_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="sendMessageToStudent('${student.email}')">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function viewStudent(studentId) {
    const student = allStudents.find(s => s.users_id == studentId);
    if (student) {
        alert(`معلومات الطالب:\nالاسم: ${student.name}\nالبريد: ${student.email}\nالهاتف: ${student.phone || 'غير محدد'}\nالجامعة: ${student.university || 'غير محدد'}\nعدد الحجوزات: ${student.bookings_count || 0}`);
    }
}

function sendMessageToStudent(email) {
    showSection('messages');
    document.getElementById('recipientType').value = 'student';
    loadRecipients();
    setTimeout(() => {
        document.getElementById('recipientSelect').value = email;
    }, 500);
}

// ============================================
// Bookings Management
// ============================================

function loadBookings() {
    fetch('api/admin/get_bookings.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allBookings = data.bookings;
                displayBookings(data.bookings);
            } else {
                console.error('Error loading bookings:', data.message);
            }
        })
        .catch(error => console.error('Error loading bookings:', error));
}

function displayBookings(bookings) {
    const tbody = document.getElementById('bookingsTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (bookings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">لا توجد بيانات</td></tr>';
        return;
    }

    bookings.forEach(booking => {
        const row = `
            <tr>
                <td>${booking.booking_id}</td>
                <td>${booking.student_name || 'غير محدد'}</td>
                <td>${booking.dorm_name || 'غير محدد'}</td>
                <td>${booking.check_in_date || 'غير محدد'}</td>
                <td>${booking.check_out_date || 'غير محدد'}</td>
                <td><span class="status-badge ${booking.status}">${getStatusText(booking.status)}</span></td>
                <td>
                    <div class="action-btns">
                        ${booking.status === 'Pending' || booking.status === 'pending' ? `
                            <button class="action-btn edit" onclick="updateBookingStatus(${booking.booking_id}, 'Confirmed')">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="action-btn delete" onclick="updateBookingStatus(${booking.booking_id}, 'Cancelled')">
                                <i class="fas fa-times"></i>
                            </button>
                        ` : ''}
                        <button class="action-btn view" onclick="viewBooking(${booking.booking_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function viewBooking(bookingId) {
    const booking = allBookings.find(b => b.booking_id == bookingId);
    if (booking) {
        alert(`معلومات الحجز:\nرقم الحجز: ${booking.booking_id}\nالطالب: ${booking.student_name}\nالسكن: ${booking.dorm_name}\nتاريخ الدخول: ${booking.check_in_date}\nتاريخ الخروج: ${booking.check_out_date}\nالحالة: ${getStatusText(booking.status)}`);
    }
}

function updateBookingStatus(bookingId, status) {
    if (!confirm(`هل أنت متأكد من ${status === 'Confirmed' ? 'تأكيد' : 'إلغاء'} هذا الحجز؟`)) {
        return;
    }

    fetch('api/admin/update_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            booking_id: bookingId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث حالة الحجز بنجاح');
            loadBookings();
        } else {
            alert('فشل تحديث حالة الحجز: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الحجز');
    });
}

// ============================================
// Comments Management
// ============================================

function loadComments() {
    fetch('api/admin/get_comments.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(data.comments);
            }
        })
        .catch(error => console.error('Error loading comments:', error));
}

function displayComments(comments) {
    const tbody = document.getElementById('commentsTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    comments.forEach(comment => {
        const row = `
            <tr>
                <td>${comment.id}</td>
                <td>${comment.name}</td>
                <td>${comment.dorm_name}</td>
                <td>${'⭐'.repeat(comment.number_of_stars)}</td>
                <td>${comment.comment.substring(0, 50)}...</td>
                <td>${comment.created_at}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn delete" onclick="deleteComment(${comment.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterComments() {
    const statusFilter = document.getElementById('commentStatusFilter').value;
    const ratingFilter = document.getElementById('commentRatingFilter').value;

    const rows = document.querySelectorAll('# tr');

    rows.forEach(row => {
        const status = row.querySelector('.status-badge').className;
        const rating = row.cells[3].textContent.length;

        let showRow = true;

        if (statusFilter && !status.includes(statusFilter)) {
            showRow = false;
        }

        if (ratingFilter && rating != ratingFilter) {
            showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
    });
}

function approveComment(commentId) {
    updateCommentStatus(commentId, 'approved');
}

function rejectComment(commentId) {
    updateCommentStatus(commentId, 'rejected');
}

function deleteComment(commentId) {
    if (confirm('هل أنت متأكد من حذف هذا التعليق؟')) {
        fetch('api/admin/delete_comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ comment_id: commentId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف التعليق بنجاح');
                loadComments();
            } else {
                alert('فشل حذف التعليق: ' + data.message);
            }
        });
    }
}

function updateCommentStatus(commentId, status) {
    fetch('api/admin/update_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ comment_id: commentId, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث حالة التعليق بنجاح');
            loadComments();
        } else {
            alert('فشل تحديث حالة التعليق: ' + data.message);
        }
    });
}

// ============================================
// Messages
// ============================================

function loadRecipients() {
    const type = document.getElementById('recipientType').value;
    const selectDiv = document.getElementById('recipientSelectDiv');
    const select = document.getElementById('recipientSelect');

    if (type === 'all_students' || type === 'all_owners') {
        selectDiv.style.display = 'none';
        return;
    }

    if (!type) {
        selectDiv.style.display = 'none';
        return;
    }

    selectDiv.style.display = 'block';

    fetch(`api/admin/get_recipients.php?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                select.innerHTML = '<option value="">اختر المستلم</option>';
                data.recipients.forEach(recipient => {
                    select.innerHTML += `<option value="${recipient.email}">${recipient.name} (${recipient.email})</option>`;
                });
            }
        });
}

function sendMessage(event) {
    event.preventDefault();

    const type = document.getElementById('recipientType').value;
    const recipient = document.getElementById('recipientSelect').value;
    const subject = document.getElementById('messageSubject').value;
    const body = document.getElementById('messageBody').value;

    if (!type) {
        alert('الرجاء اختيار نوع المستلم');
        return;
    }

    if ((type === 'student' || type === 'owner') && !recipient) {
        alert('الرجاء اختيار المستلم');
        return;
    }

    fetch('api/admin/send_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: type,
            recipient: recipient,
            subject: subject,
            body: body
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال الرسالة بنجاح');
            document.getElementById('messageForm').reset();
        } else {
            alert('فشل إرسال الرسالة: ' + data.message);
        }
    });
}

// ============================================
// Utility Functions
// ============================================

function getStatusText(status) {
    const statusMap = {
        'Pending': 'معلق',
        'Confirmed': 'مؤكد',
        'Cancelled': 'ملغي',
        'pending': 'معلق',
        'approved': 'موافق عليه',
        'rejected': 'مرفوض',
        'active': 'نشط',
        'inactive': 'غير نشط'
    };

    return statusMap[status] || status;
}

function exportReport(type) {
    window.location.href = `api/admin/export_report.php?type=${type}`;
}

function saveSettings(event) {
    event.preventDefault();

    const settings = {
        site_name: document.getElementById('siteName').value,
        site_email: document.getElementById('siteEmail').value,
        site_phone: document.getElementById('sitePhone').value
    };

    fetch('api/admin/save_settings.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم حفظ الإعدادات بنجاح', 'success');
        } else {
            showAlert('فشل حفظ الإعدادات: ' + data.message, 'error');
        }
    });
}

// Setup form handlers
function setupFormHandlers() {
    // Add Dorm Form
    const addDormBtn = document.getElementById('addDormBtn');
    if (addDormBtn) {
        addDormBtn.addEventListener('click', showAddDormModal);
    }

    // Add Owner Form
    const addOwnerBtn = document.getElementById('addOwnerBtn');
    if (addOwnerBtn) {
        addOwnerBtn.addEventListener('click', showAddOwnerModal);
    }
}

// Show add dorm modal
function showAddDormModal() {
    const modal = document.getElementById('addDormModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Show add owner modal
function showAddOwnerModal() {
    const modal = document.getElementById('addOwnerModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Show alert
function showAlert(message, type = 'info') {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 30px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideDown 0.3s ease;
    `;
    alert.textContent = message;

    document.body.appendChild(alert);

    // Remove after 3 seconds
    setTimeout(() => {
        alert.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 3000);
}

// Add dorm
function addDorm(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('dormName').value,
        location: document.getElementById('dormLocation').value,
        gender: document.getElementById('dormGender').value,
        description: document.getElementById('dormDescription').value,
        price_range: document.getElementById('dormPriceRange').value,
        room_type: document.getElementById('dormRoomType').value,
        owner_id: document.getElementById('dormOwnerId').value || null
    };

    fetch('api/admin/add_dorm.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم إضافة السكن بنجاح', 'success');
            closeModal('addDormModal');
            loadDorms();
            event.target.reset();
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('خطأ في الاتصال بالخادم', 'error');
        console.error('Error:', error);
    });
}

// Add owner
function addOwner(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('ownerName').value,
        email: document.getElementById('ownerEmail').value,
        password: document.getElementById('ownerPassword').value,
        gender: document.getElementById('ownerGender').value,
        phone: document.getElementById('ownerPhone').value,
        address: document.getElementById('ownerAddress').value
    };

    fetch('api/admin/add_owner.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم إضافة المالك بنجاح', 'success');
            closeModal('addOwnerModal');
            loadOwners();
            event.target.reset();
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('خطأ في الاتصال بالخادم', 'error');
        console.error('Error:', error);
    });
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        to {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// Modal Functions
// ============================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
        setTimeout(() => {
            event.target.style.display = 'none';
        }, 300);
    }
}

// ============================================
// Edit Dorm Function
// ============================================

function editDorm(dormId) {
    const dorm = allDorms.find(d => d.dorm_id === dormId);
    if (!dorm) return;

    document.getElementById('editDormId').value = dorm.dorm_id;
    document.getElementById('editDormName').value = dorm.name;
    document.getElementById('editDormLocation').value = dorm.location;
    document.getElementById('editDormGender').value = dorm.gender;
    document.getElementById('editDormDescription').value = dorm.description || '';
    document.getElementById('editDormPriceRange').value = dorm.price_range || '';
    document.getElementById('editDormStatus').value = dorm.status || 'active';

    openModal('editDormModal');
}

function updateDormSubmit(event) {
    event.preventDefault();

    const formData = {
        dorm_id: document.getElementById('editDormId').value,
        name: document.getElementById('editDormName').value,
        location: document.getElementById('editDormLocation').value,
        gender: document.getElementById('editDormGender').value,
        description: document.getElementById('editDormDescription').value,
        price_range: document.getElementById('editDormPriceRange').value,
        status: document.getElementById('editDormStatus').value
    };

    fetch('api/admin/update_dorm.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تم تحديث السكن بنجاح', 'success');
            closeModal('editDormModal');
            loadDorms();
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('خطأ في الاتصال بالخادم', 'error');
        console.error('Error:', error);
    });
}

// ============================================
// Load Owners for Dropdown
// ============================================

function loadOwnersForDropdown() {
    fetch('api/admin/get_owners.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('dormOwnerId');
                if (select) {
                    select.innerHTML = '<option value="">اختر المالك</option>';
                    data.owners.forEach(owner => {
                        select.innerHTML += `<option value="${owner.users_id}">${owner.name} (${owner.email})</option>`;
                    });
                }
            }
        })
        .catch(error => console.error('Error loading owners:', error));
}

// Load owners when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadOwnersForDropdown();
});

// ============================================
// Amenities Management
// ============================================

let currentDormIdForAmenities = null;

async function manageDormAmenities(dormId, dormName) {
    currentDormIdForAmenities = dormId;
    document.getElementById('amenitiesModalDormName').textContent = dormName;
    document.getElementById('amenitiesModal').style.display = 'flex';

    // Load amenities
    try {
        const response = await fetch(`api/admin/get_dorm_amenities.php?dorm_id=${dormId}`);
        const data = await response.json();

        if (data.success) {
            displayAmenitiesInModal(data.amenities);
        } else {
            document.getElementById('amenitiesModalBody').innerHTML = `<p style="color: red; text-align: center;">${data.message}</p>`;
        }
    } catch (error) {
        console.error('Error loading amenities:', error);
        document.getElementById('amenitiesModalBody').innerHTML = '<p style="color: red; text-align: center;">خطأ في تحميل المرافق</p>';
    }
}

function displayAmenitiesInModal(amenities) {
    const body = document.getElementById('amenitiesModalBody');

    if (!amenities || amenities.length === 0) {
        body.innerHTML = '<p style="text-align: center; color: #6b7280;">لا توجد مرافق متاحة</p>';
        return;
    }

    let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">';

    amenities.forEach(amenity => {
        html += `
            <div style="padding: 15px; border: 2px solid ${amenity.is_selected ? '#10b981' : '#e5e7eb'}; border-radius: 8px; background: ${amenity.is_selected ? '#f0fdf4' : 'white'};">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox"
                        data-amenity-id="${amenity.amenity_id}"
                        ${amenity.is_selected ? 'checked' : ''}
                        style="width: 20px; height: 20px; cursor: pointer;">
                    <span style="font-weight: 500; color: #1e3a8a;">${amenity.amenity_name}</span>
                </label>
            </div>
        `;
    });

    html += '</div>';
    body.innerHTML = html;
}

async function saveAmenitiesFromModal() {
    if (!currentDormIdForAmenities) return;

    const checkboxes = document.querySelectorAll('#amenitiesModalBody input[type="checkbox"]');
    const amenity_ids = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            amenity_ids.push(parseInt(checkbox.getAttribute('data-amenity-id')));
        }
    });

    try {
        const response = await fetch('api/admin/update_dorm_amenities.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: currentDormIdForAmenities,
                amenity_ids: amenity_ids
            })
        });

        const data = await response.json();
        if (data.success) {
            alert('تم حفظ المرافق بنجاح');
            closeAmenitiesModal();
        } else {
            alert('خطأ: ' + data.message);
        }
    } catch (error) {
        console.error('Error saving amenities:', error);
        alert('خطأ في حفظ المرافق');
    }
}

function closeAmenitiesModal() {
    document.getElementById('amenitiesModal').style.display = 'none';
    currentDormIdForAmenities = null;
}

// ============================================
// Open Modal Functions
// ============================================

function openAddDormModal() {
    loadOwnersForDropdown();
    openModal('addDormModal');
}

function openAddOwnerModal() {
    openModal('addOwnerModal');
}
