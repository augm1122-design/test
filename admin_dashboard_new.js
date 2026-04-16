// ============================================
// Admin Dashboard JavaScript - FULL WORKING VERSION
// ============================================

// Global variables
let currentSection = 'dashboard';
let allDorms = [];
let allOwners = [];
let allStudents = [];
let allComments = [];
let dormsColumns = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    setupNavigation();
    setupMenuToggle();
    loadDorms();
    loadOwners();
    loadStudents();
    loadComments();
}

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

function showSection(sectionName) {
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) targetSection.classList.add('active');

    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    const activeNav = document.querySelector(`.nav-item[data-section="${sectionName}"]`);
    if (activeNav) activeNav.classList.add('active');

    currentSection = sectionName;
    reloadSectionData(sectionName);

    // --- الإضافة هنا ---
    // انتظر قليلاً حتى يتم تحميل البيانات ثم طبق الترجمة
    setTimeout(() => {
        const savedLang = localStorage.getItem('adminLang') || 'ar';
        switchLanguage(savedLang);
    }, 100); 
}
function reloadSectionData(sectionName) {
    switch(sectionName) {
        case 'dorms': loadDorms(); break;
        case 'owners': loadOwners(); break;
        case 'students': loadStudents(); break;
        case 'comments': loadComments(); break;
    }
}

function setupMenuToggle() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebar && menuToggle) {
        menuToggle.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
    }
}


async function toggleNotificationsPanel() {
    let overlay = document.getElementById('notificationsOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'notificationsOverlay';
        document.body.appendChild(overlay);
    }
    
    // تنسيق يضمن الظهور في المنتصف
    overlay.style.cssText = "display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:999999; justify-content:center; align-items:center; direction:rtl;";
    
    overlay.innerHTML = `
        <div style="background:white; width:95%; max-width:600px; border-radius:15px; display:flex; flex-direction:column; max-height:80vh; box-shadow:0 10px 30px rgba(0,0,0,0.5);">
            <div style="padding:15px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                <h3 id="statusTitle" style="margin:0; color:#333;">🔔 الإشعارات</h3>
                <button onclick="closeNotificationsPanel()" style="background:none; border:none; font-size:30px; cursor:pointer;">&times;</button>
            </div>
            <div id="notificationsBody" style="padding:20px; overflow-y:auto; flex:1; min-height:200px; background:#fff;">
                <p style="text-align:center;">جاري عرض محتوى الرسائل...</p>
            </div>
        </div>`;

    const body = document.getElementById('notificationsBody');
    const title = document.getElementById('statusTitle');

    try {
        const response = await fetch('api/admin/get_notifications.php');
        const data = await response.json();

        if (data.success && data.notifications) {
            const notifs = data.notifications;
            title.innerText = `📩 رسائل جديدة (${notifs.length})`;
            
            // مسح أي محتوى قديم
            body.innerHTML = '';

            // تكرار لعرض كل رسالة
            notifs.forEach(n => {
                // استخراج البيانات مع قيم افتراضية في حال كانت فارغة
                const id = n.id || 0;
                const email = n.sender_email || 'Email missing';
                const msg = n.message || 'No content';
                const sub = n.subject || 'No subject';
                const reply = n.admin_reply || '';

                // إنشاء عنصر الرسالة يدوياً
                const card = document.createElement('div');
                card.style.cssText = "border:1px solid #ddd; padding:15px; margin-bottom:15px; border-radius:10px; text-align:right; background:#f9f9f9;";
                
                card.innerHTML = `
                    <strong style="color:#007bff; display:block;">من: ${email}</strong>
                    <div style="margin:10px 0; background:white; padding:10px; border-radius:5px; border:1px solid #eee;">
                        <small style="color:#666;">الموضوع: ${sub}</small>
                        <p style="margin:5px 0; color:#333;">${msg}</p>
                    </div>
                    ${reply ? `<div style="background:#e6fffa; padding:10px; border-right:4px solid #38b2ac; margin-bottom:10px;"><strong>ردك:</strong> ${reply}</div>` : ''}
                    <textarea id="reply-text-${id}" placeholder="اكتب ردك هنا..." style="width:100%; height:60px; border:1px solid #ccc; border-radius:5px; padding:5px;"></textarea>
                    <button onclick="handleReply(${id}, '${email}', '${sub}')" style="width:100%; background:#007bff; color:white; border:none; padding:10px; margin-top:5px; border-radius:5px; cursor:pointer; font-weight:bold;">إرسال الرد</button>
                `;
                body.appendChild(card);
            });
            
            // إذا وصلنا هنا ولم يظهر شيء، سيعطيك المتصفح تنبيهاً
            if (body.innerHTML === '') {
                body.innerHTML = '<p style="text-align:center;">لا توجد رسائل لعرضها</p>';
            }

        } else {
            body.innerHTML = '<p style="text-align:center;">فشل في جلب البيانات من السيرفر</p>';
        }
    } catch (error) {
        body.innerHTML = '<p style="color:red; text-align:center;">خطأ تقني: ' + error.message + '</p>';
    }
}
// ============================================
// DORMS MANAGEMENT
// ============================================

async function loadDorms() {
    try {
        const response = await fetch('api/admin/manage_dorms.php?action=get_all');
        const result = await response.json();
        if (result.success) {
            allDorms = result.data;
            const colResponse = await fetch('api/admin/manage_dorms.php?action=get_columns');
            const colResult = await colResponse.json();
            if (colResult.success) {
                dormsColumns = colResult.columns;
                displayDormsTable();
            }
        }
    } catch (error) { console.error(error); }
}

function displayDormsTable() {
    const thead = document.getElementById('dormsTableHead');
    const tbody = document.getElementById('dormsTableBody');
    if (!thead || !tbody) return;
    thead.innerHTML = `<tr>${dormsColumns.map(c => `<th>${c.Field}</th>`).join('')}<th>الإجراءات</th></tr>`;
    tbody.innerHTML = allDorms.map(dorm => `
        <tr data-id="${dorm.dorm_id}">
            ${dormsColumns.map(c => c.Field === 'dorm_id' ? `<td>${dorm[c.Field]}</td>` : `<td contenteditable="true" data-field="${c.Field}">${dorm[c.Field] || ''}</td>`).join('')}
            <td>
                <button class="btn btn-sm btn-success" onclick="saveDormRow(${dorm.dorm_id})"><i class="fas fa-save"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteDorm(${dorm.dorm_id})"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`).join('');
}

async function saveDormRow(dormId) {
    const row = document.querySelector(`tr[data-id="${dormId}"]`);
    const data = { dorm_id: dormId };
    row.querySelectorAll('td[contenteditable="true"]').forEach(cell => data[cell.getAttribute('data-field')] = cell.textContent.trim());
    const res = await fetch('api/admin/manage_dorms.php?action=update', { method: 'POST', body: JSON.stringify(data) });
    const result = await res.json();
    showNotification(result.success ? 'تم الحفظ' : result.message, result.success ? 'success' : 'error');
}

// ============================================
// OWNERS & STUDENTS MANAGEMENT
// ============================================

async function loadOwners() {
    const res = await fetch('api/admin/manage_owners.php?action=get_all');
    const result = await res.json();
    if (result.success) { allOwners = result.data; displayOwners(); }
}

function displayOwners() {
    const tbody = document.getElementById('ownersTableBody');
    if (!tbody) return;
    tbody.innerHTML = allOwners.map(o => `
        <tr>
            <td>${o.users_id}</td>
            <td contenteditable="true" data-id="${o.users_id}" data-field="name">${o.name || ''}</td>
            <td contenteditable="true" data-id="${o.users_id}" data-field="email">${o.email || ''}</td>
            <td contenteditable="true" data-id="${o.users_id}" data-field="phone">${o.phone || ''}</td>
            <td>${o.dorm_name || 'لا يوجد'}</td>
            <td><button class="btn btn-sm btn-success" onclick="saveOwner(${o.users_id})"><i class="fas fa-save"></i></button></td>
        </tr>`).join('');
}

async function loadStudents() {
    const res = await fetch('api/admin/manage_students.php?action=get_all');
    const result = await res.json();
    if (result.success) { allStudents = result.data; displayStudents(); }
}

function displayStudents() {
    const tbody = document.getElementById('studentsTableBody');
    if (!tbody) return;
    tbody.innerHTML = allStudents.map(s => `
        <tr>
            <td>${s.users_id}</td>
            <td contenteditable="true" data-id="${s.users_id}" data-field="name">${s.name || ''}</td>
            <td contenteditable="true" data-id="${s.users_id}" data-field="email">${s.email || ''}</td>
            <td contenteditable="true" data-id="${s.users_id}" data-field="phone">${s.phone || ''}</td>
            <td>${s.bookings_count || 0}</td>
            <td><button class="btn btn-sm btn-success" onclick="saveStudent(${s.users_id})"><i class="fas fa-save"></i></button></td>
        </tr>`).join('');
}

// ============================================
// COMMENTS MANAGEMENT
// ============================================

async function loadComments() {
    const res = await fetch('api/admin/manage_comments.php?action=get_all');
    const result = await res.json();
    if (result.success) { allComments = result.data; displayComments(); }
}

function displayComments() {
    const tbody = document.getElementById('commentsTableBody');
    const thead = document.querySelector('#commentsTable thead'); // تأكد من استهداف الرأس
    
    if (!tbody || !thead) return;

    // ترجمة العناوين ديناميكياً
    thead.innerHTML = `
        <tr>
            <th data-lang="comment_id">المعرف</th>
            <th data-lang="commenter_name">اسم المعلق</th>
            <th data-lang="dorm_name">السكن</th>
            <th data-lang="rating">التقييم</th>
            <th data-lang="comment">التعليق</th>
            <th data-lang="date">التاريخ</th>
            <th data-lang="actions">الإجراءات</th>
        </tr>`;

    tbody.innerHTML = allComments.map(c => `
        <tr>
            <td>${c.comment_id}</td>
            <td>${c.user_name}</td>
            <td>${c.dorm_name}</td>
            <td>${'⭐'.repeat(c.rating || 0)}</td>
            <td>${c.comment}</td>
            <td>${c.created_at}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteComment(${c.comment_id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`).join('');

    // استدعاء الترجمة فوراً بعد رسم الجدول
    switchLanguage(localStorage.getItem('adminLang') || 'ar');
}
// ============================================
// NOTIFICATIONS & MESSAGES (Centred Popup)
// ============================================



function closeNotificationsPanel() {
    const overlay = document.getElementById('notificationsOverlay');
    if (overlay) overlay.style.display = 'none';
}

async function handleReply(msgId, email, subject) {
    // جلب النص من المعرف الصحيح
    const textArea = document.getElementById(`reply-text-${msgId}`);
    const text = textArea ? textArea.value.trim() : "";

    if (!text) { 
        alert("يرجى كتابة نص الرد"); 
        return; 
    }

    try {
        const response = await fetch('api/admin/send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: 'student', // أو حسب منطق قاعدة بياناتك
                recipient: email,
                subject: "رد على: " + subject,
                body: text,
                message_id: msgId
            })
        });
        
        const result = await response.json();
        if (result.success) {
            alert("تم إرسال الرد بنجاح ✅");
            // مسح النص بعد الإرسال أو إغلاق النافذة
            textArea.value = "";
            showAdminNotifications(); // لتحديث القائمة
        } else {
            alert("فشل الإرسال: " + result.message);
        }
    } catch (e) {
        alert("فشل في الاتصال بالسيرفر");
    }
}

async function sendMessage(event) {
    event.preventDefault();
    const type = document.getElementById('recipientType').value;
    const data = {
        type: type,
        subject: document.getElementById('messageSubject').value,
        body: document.getElementById('messageBody').value,
        recipient: document.getElementById('recipientSelect').value
    };
    const res = await fetch('api/admin/send_message.php', { method: 'POST', body: JSON.stringify(data) });
    const result = await res.json();
    if (result.success) { showNotification('تم الإرسال', 'success'); document.getElementById('messageForm').reset(); }
}

function showNotification(msg, type) {
    const div = document.createElement('div');
    div.style.cssText = `position:fixed; top:20px; right:20px; padding:15px; background:${type==='success'?'#4CAF50':'#f44336'}; color:white; z-index:11000; border-radius:5px;`;
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}



// دالة عرض الرسائل عند الضغط على الجرس
async function showAdminNotifications() {
    const overlayId = 'notificationsOverlay';
    let overlay = document.getElementById(overlayId);
    
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = overlayId;
        document.body.appendChild(overlay);
    }

    // تصميم النافذة المنبثقة لتظهر بوضوح في منتصف الشاشة
    overlay.style.cssText = "display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:999999; justify-content:center; align-items:center; direction:rtl;";

    overlay.innerHTML = `
        <div style="background:white; width:95%; max-width:550px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); display:flex; flex-direction:column; max-height:85vh;">
            <div style="padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; color:#333; font-family: sans-serif;">🔔 الرسائل الواردة</h3>
                <button onclick="document.getElementById('notificationsOverlay').style.display='none'" style="background:none; border:none; font-size:35px; cursor:pointer; color:#999;">&times;</button>
            </div>
            <div id="finalBody" style="padding:20px; overflow-y:auto; flex:1; background:#fff;">
                <p style="text-align:center;">جاري تحميل الرسائل...</p>
            </div>
        </div>`;

    overlay.style.display = 'flex';
    const body = document.getElementById('finalBody');

    try {
        const response = await fetch('api/admin/get_notifications.php');
        const data = await response.json();

        if (data.success && data.notifications && data.notifications.length > 0) {
            let html = '';
            data.notifications.forEach(n => {
                html += `
                    <div style="border:1px solid #eee; padding:15px; margin-bottom:15px; border-radius:12px; background:#fafafa; text-align:right;">
                        <strong style="color:#007bff; display:block; margin-bottom:5px;">من: ${n.sender_email}</strong>
                        <div style="background:white; padding:12px; border:1px solid #efefef; border-radius:8px;">
                            <small style="color:#888; display:block; margin-bottom:5px;">الموضوع: ${n.subject}</small>
                            <p style="margin:0; line-height:1.5; color:#333;">${n.message}</p>
                        </div>
                        <div style="margin-top:15px;">
                            <textarea id="reply-text-${n.id}" placeholder="اكتب ردك هنا للمستخدم..." style="width:100%; border:1px solid #ddd; padding:10px; border-radius:8px; height:70px; resize:none; font-family:inherit;"></textarea>
                            <button onclick="handleReply(${n.id}, '${n.sender_email}', '${n.subject}')" style="width:100%; margin-top:8px; background:#28a745; color:white; border:none; padding:12px; border-radius:8px; cursor:pointer; font-weight:bold; transition: 0.3s;">إرسال الرد</button>
                        </div>
                    </div>`;
            });
            body.innerHTML = html;
        } else {
            body.innerHTML = '<p style="text-align:center; padding:40px; color:#666;">لا توجد رسائل جديدة حالياً.</p>';
        }
    } catch (e) {
        body.innerHTML = '<p style="color:red; text-align:center; padding:20px;">حدث خطأ في الاتصال بالسيرفر. تأكد من ملف get_notifications.php</p>';
    }
}

// دالة معالجة الرد وإرساله
async function handleReply(msgId, email, subject) {
    const replyInput = document.getElementById(`reply-text-${msgId}`);
    const replyText = replyInput ? replyInput.value.trim() : "";

    if (!replyText) {
        alert("يا حبيبي لازم تكتب نص الرد أولاً!");
        return;
    }

    try {
        const response = await fetch('api/admin/send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: 'student', 
                recipient: email,
                subject: "رد من الإدارة: " + subject,
                body: replyText,
                message_id: msgId
            })
        });

        const result = await response.json();
        if (result.success) {
            alert("✅ تم إرسال الرد بنجاح");
            showAdminNotifications(); // تحديث القائمة لإخفاء الرسالة أو تحديثها
        } else {
            alert("❌ فشل الإرسال: " + result.message);
        }
    } catch (e) {
        alert("خطأ تقني: لم نتمكن من الوصول لملف الإرسال.");
    }
}

function filterComments() {
    // 1. الحصول على القيمة من القائمة المنسدلة
    const ratingValue = document.getElementById('commentRatingFilter').value;
    const tbody = document.getElementById('commentsTableBody');
    
    if (!allComments || !tbody) return;

    // 2. تصفية المصفوفة
    // ملاحظة: نستخدم c.rating لأن PHP يرسلها بهذا الاسم
    const filtered = ratingValue === "" 
        ? allComments 
        : allComments.filter(c => parseInt(c.rating) === parseInt(ratingValue));

    // 3. إعادة رسم الجدول
    tbody.innerHTML = filtered.map(c => `
        <tr>
            <td>${c.comment_id}</td>
            <td>${c.user_name}</td>
            <td>${c.dorm_name}</td>
            <td>${'⭐'.repeat(c.rating || 0)}</td>
            <td>${c.comment}</td>
            <td>${c.created_at}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteComment(${c.comment_id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`).join('');
}

async function deleteComment(id) {
    if (!confirm('هل أنت متأكد من حذف هذا التعليق؟')) return;

    try {
        const response = await fetch('api/admin/manage_comments.php?action=delete', {
            method: 'POST', // كود الـ PHP يقرأ input body لذا نستخدم POST
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment_id: id })
        });

        const result = await response.json();
        if (result.success) {
            alert('تم الحذف بنجاح');
            loadComments(); // إعادة تحميل الجدول
        } else {
            alert('خطأ: ' + result.message);
        }
    } catch (e) {
        console.error(e);
        alert('حدث خطأ في الاتصال');
    }
}