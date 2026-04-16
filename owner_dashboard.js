// ============================================
// Owner Dashboard JavaScript - Enhanced Version
// ============================================

// دالة لتنظيف النصوص وحماية الموقع من الأكواد الخبيثة
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Global variables
let currentSection = 'dashboard';
let allBookings = [];
let allComments = [];
let allNotifications = [];
let allDormData = {};
let allFacilities = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    loadDashboardData();
});

// Initialize dashboard
function initializeDashboard() {
    setupNavigation();
    setupMenuToggle();

    if (dormId > 0) {
        loadDormInfo();
        loadFacilities();
        loadDashboardAmenities(); // Load amenities for dashboard
        loadRoomPrices();
        loadBookings();
        loadComments();
        loadNotifications();

        // Auto-refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    }
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

    // إضافة استدعاء الترجمة بعد تغيير القسم
    setTimeout(() => {
        const savedLang = localStorage.getItem('adminLang') || 'ar';
        switchLanguage(savedLang);
    }, 50);
}
// Setup menu toggle
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
    console.log('Owner dashboard initialized for dorm:', dormId);
}

// ============================================
// Dorm Management
// ============================================

async function loadDormInfo() {
    try {
        const response = await fetch(`api/owner/get_dorm_info.php?dorm_id=${dormId}`);
        const data = await response.json();

        if (data.success) {
            allDormData = data.dorm;
            displayDormInfo(data.dorm);
        }
    } catch (error) {
        console.error('Error loading dorm info:', error);
    }
}

function displayDormInfo(dorm) {
    const tbody = document.getElementById('dormInfoTableBody');
    if (!tbody) return;

    // 1. تعريف الحقول المطلوب عرضها
    const fields = [
        { key: 'dorm_id', label_ar: 'رقم السكن', label_en: 'Dorm ID', editable: false },
        { key: 'name', label_ar: 'اسم السكن', label_en: 'Dorm Name', editable: true },
        { key: 'location', label_ar: 'الموقع', label_en: 'Location', editable: true },
        { key: 'description', label_ar: 'الوصف', label_en: 'Description', editable: true },
        { key: 'room_type', label_ar: 'نوع الغرفة', label_en: 'Room Type', editable: true },
        { key: 'price_range', label_ar: 'نطاق الأسعار', label_en: 'Price Range', editable: true },
        { key: 'gender', label_ar: 'النوع (ذكور/إناث)', label_en: 'Gender', editable: true },
        { key: 'image', label_ar: 'رابط الصورة', label_en: 'Image URL', editable: true },
        { key: 'status', label_ar: 'الحالة', label_en: 'Status', editable: true }
    ];

    let html = '';
    const currentLang = localStorage.getItem('adminLang') || 'ar';

    // 2. تكرار الحقول لإنشاء صفوف الجدول
    fields.forEach(field => {
        const value = dorm[field.key] || '';
        const label = currentLang === 'ar' ? field.label_ar : field.label_en;
        const saveText = currentLang === 'ar' ? 'حفظ' : 'Save';

        html += `<tr>
            <td><strong>${label}</strong></td>
            <td ${field.editable ? 'contenteditable="true"' : ''} 
                data-field="${field.key}" 
                style="${!field.editable ? 'background-color: #f5f5f5; color: #666;' : 'background-color: #fff;'}">
                ${value}
            </td>
            <td>
                ${field.editable ? `
                    <button class="btn btn-sm btn-success" onclick="saveSingleField('${field.key}')">
                        <i class="fas fa-save"></i> ${saveText}
                    </button>
                ` : '-'}
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
    
    // 3. تحديث الترجمة بعد إضافة العناصر للـ DOM
    if (typeof switchLanguage === 'function') {
        switchLanguage(currentLang);
    }
}

async function saveSingleField(fieldName) {
    const cell = document.querySelector(`td[data-field="${fieldName}"]`);
    const value = cell.textContent.trim();

    try {
        const response = await fetch('api/owner/update_dorm.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: dormId,
                field: fieldName,
                value: value
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ التغييرات بنجاح', 'success');
        } else {
            showNotification(data.message || 'خطأ في الحفظ', 'error');
        }
    } catch (error) {
        console.error('Error saving field:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

async function saveDormInfo() {
    const cells = document.querySelectorAll('#dormInfoTableBody td[contenteditable="true"]');
    const updates = {};

    cells.forEach(cell => {
        const field = cell.getAttribute('data-field');
        updates[field] = cell.textContent.trim();
    });

    try {
        const response = await fetch('api/owner/update_dorm.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: dormId,
                updates: updates
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ جميع التغييرات بنجاح', 'success');
            loadDormInfo();
        } else {
            showNotification(data.message || 'خطأ في الحفظ', 'error');
        }
    } catch (error) {
        console.error('Error saving dorm info:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

// ============================================
// Amenities Management (المرافق)
// ============================================

let allAmenities = [];

async function loadFacilities() {
    try {
        const response = await fetch(`api/owner/get_amenities.php?dorm_id=${dormId}`);
        const data = await response.json();

        console.log('Amenities Response:', data);

        if (data.success) {
            allAmenities = data.amenities;
            displayFacilities(data.amenities);
        } else {
            console.error('Failed to load amenities:', data.message);
            const tbody = document.getElementById('facilitiesTableBody');
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="3" style="text-align: center; color: red;">${data.message || 'خطأ في تحميل المرافق'}</td></tr>`;
            }
        }
    } catch (error) {
        console.error('Error loading amenities:', error);
        const tbody = document.getElementById('facilitiesTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="3" style="text-align: center; color: red;">خطأ في الاتصال بالخادم</td></tr>`;
        }
    }
}

function displayFacilities(amenities) {
    const tbody = document.getElementById('facilitiesTableBody');
    if (!tbody) return;

    const currentLang = localStorage.getItem('adminLang') || 'ar';

    let html = '';
    amenities.forEach(amenity => {
        const amenityName = currentLang === 'ar' ? amenity.amenity_name : (amenity.amenity_name_en || amenity.amenity_name);
        html += `<tr>
            <td>${amenityName}</td>
            <td>
                <input type="checkbox"
                    data-amenity-id="${amenity.amenity_id}"
                    ${amenity.is_selected ? 'checked' : ''}
                    style="width: 20px; height: 20px; cursor: pointer;">
            </td>
            <td>
    <span style="font-size: 12px; color: #6b7280;">
        ${amenity.is_selected ? (currentLang === 'ar' ? 'متاح' : 'Available') : (currentLang === 'ar' ? 'غير متاح' : 'Not available')}
    </span>
</td>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
}

async function saveFacilities() {
    const checkboxes = document.querySelectorAll('#facilitiesTableBody input[type="checkbox"]');
    const amenity_ids = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            amenity_ids.push(parseInt(checkbox.getAttribute('data-amenity-id')));
        }
    });

    try {
        const response = await fetch('api/owner/update_amenities.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: dormId,
                amenity_ids: amenity_ids
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ جميع المرافق بنجاح', 'success');
            loadFacilities(); // Reload to refresh
            loadDashboardAmenities(); // Reload dashboard amenities too
        } else {
            showNotification(data.message || 'خطأ في الحفظ', 'error');
        }
    } catch (error) {
        console.error('Error saving amenities:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

// Load amenities for dashboard display
async function loadDashboardAmenities() {
    try {
        const response = await fetch(`api/owner/get_amenities.php?dorm_id=${dormId}`);
        const data = await response.json();

        if (data.success) {
            displayDashboardAmenities(data.amenities);
        }
    } catch (error) {
        console.error('Error loading dashboard amenities:', error);
    }
}

function displayDashboardAmenities(amenities) {
    const container = document.getElementById('dashboardAmenitiesContainer');
    if (!container) return;

    const currentLang = localStorage.getItem('adminLang') || 'ar';

    // Filter only selected amenities
    const selectedAmenities = amenities.filter(a => a.is_selected);

    if (selectedAmenities.length === 0) {
        container.innerHTML = `<p style="color: #6b7280;">${currentLang === 'ar' ? 'لا توجد مرافق محددة' : 'No amenities selected'}</p>`;
        return;
    }

    let html = '';
    selectedAmenities.forEach(amenity => {
        const amenityName = currentLang === 'ar' ? amenity.amenity_name : (amenity.amenity_name_en || amenity.amenity_name);
        const icon = amenity.icon || 'fa-check-circle';

        html += `
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px; border-right: 3px solid #3b82f6;">
                <i class="fas ${icon}" style="color: #3b82f6; font-size: 18px;"></i>
                <span style="color: #1e3a8a; font-weight: 500;">${amenityName}</span>
            </div>
        `;
    });

    container.innerHTML = html;
}

// ============================================
// Room Prices Management
// ============================================

async function loadRoomPrices() {
    try {
        // Load all rooms with details (using new API)
        const response = await fetch(`api/owner/get_rooms.php?dorm_id=${dormId}`);
        const data = await response.json();

        console.log('Rooms Response:', data);

        if (data.success) {
            window.currentRooms = data.rooms; // Store for later use
            displayAvailableRooms(data.rooms);
        } else {
            console.error('Failed to load rooms:', data.message);
            const tbody = document.getElementById('roomPricesTableBody');
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: red;">${data.message || 'خطأ في تحميل الغرف'}</td></tr>`;
            }
        }
    } catch (error) {
        console.error('Error loading rooms:', error);
        const tbody = document.getElementById('roomPricesTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: red;">خطأ في الاتصال بالخادم</td></tr>`;
        }
    }
}

function displayAvailableRooms(rooms) {
    const tbody = document.getElementById('roomPricesTableBody');
    if (!tbody) return;

    const currentLang = localStorage.getItem('adminLang') || 'ar';

    const roomTypeLabels = {
        'single': currentLang === 'ar' ? 'فردية' : 'Single',
        'double': currentLang === 'ar' ? 'ثنائية' : 'Double',
        'triple': currentLang === 'ar' ? 'ثلاثية' : 'Triple',
        'suite': currentLang === 'ar' ? 'جناح' : 'Suite'
    };

    if (!rooms || rooms.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">${currentLang === 'ar' ? 'لا توجد غرف' : 'No rooms found'}</td></tr>`;
        return;
    }

    let html = '';
    rooms.forEach(room => {
        // تحديد نوع الغرفة بناءً على اللغة
        const roomType = roomTypeLabels[(room.room_type || '').toLowerCase()] || room.room_type || '-';

        // معالجة بيانات الطلاب المحجوزين لعرضها في الخلية
        // ملاحظة: استبدلنا \n بـ <br> للعرض داخل الجدول
        let occupantsSummary = (room.occupants && room.occupants !== 'لا يوجد حاجزين حالياً') 
            ? escapeHtml(room.occupants).replace(/\n/g, '<br>') 
            : (currentLang === 'ar' ? 'غير محجوزة' : 'Not Booked');

        const capacity = room.capacity || 0;
        const currentOcc = room.current_occupancy || 0;
        const availableSpots = room.available_spots || 0;
        const availabilityText = room.is_available == 1 ? (currentLang === 'ar' ? 'متاحة' : 'Available') : (currentLang === 'ar' ? 'محجوزة بالكامل' : 'Fully Booked');

        html += `<tr>
            <td contenteditable="true" class="editable-cell" data-room-id="${room.room_id}" data-field="room_number">${escapeHtml(room.room_number) || '-'}</td>
            <td contenteditable="true" class="editable-cell" data-room-id="${room.room_id}" data-field="room_type">${escapeHtml(roomType)}</td>
            <td contenteditable="true" class="editable-cell" data-room-id="${room.room_id}" data-field="price">${room.price || 0}</td>
            <td>
                <div style="font-weight:600; margin-bottom:6px; color: #1e3a8a;">${occupantsSummary}</div>
                <div style="font-size:12px; color:#6b7280;">
                    ${currentLang === 'ar' ? 'سعة:' : 'Capacity:'} ${capacity} &nbsp; | &nbsp; 
                    ${currentLang === 'ar' ? 'مشغول:' : 'Occupied:'} ${currentOcc} &nbsp; | &nbsp; 
                    ${currentLang === 'ar' ? 'متاح:' : 'Available:'} ${availableSpots}
                </div>
                <div style="font-size:11px; color:#9ca3af; margin-top:4px;">
                    ${currentLang === 'ar' ? 'الحالة:' : 'Status:'} ${availabilityText}
                </div>
            </td>
            <td>
                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                    <button class="btn btn-sm btn-success" onclick="saveRoomInfo(${room.room_id})" title="${currentLang === 'ar' ? 'حفظ' : 'Save'}">
                        <i class="fas fa-save"></i>
                    </button>
                    <button class="btn btn-sm btn-info" 
                            onclick="viewRoomOccupants(${room.room_id}, '${escapeHtml(room.room_number)}', \`${escapeHtml(room.occupants)}\`)" 
                            title="${currentLang === 'ar' ? 'عرض الطلاب' : 'View Students'}">
                        <i class="fas fa-users"></i> ${currentLang === 'ar' ? 'الحاجزين' : 'Occupants'}
                    </button>
                </div>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;

    // استدعاء دالة الترجمة لضمان تعريب أي نصوص ثابتة مضافة
    if (typeof switchLanguage === 'function') {
        switchLanguage(currentLang);
    }
}

// View occupants in a room
function viewRoomOccupants(roomId, roomNumber, occupants) {
    const currentLang = localStorage.getItem('adminLang') || 'ar';

    if (!occupants || occupants === 'لا يوجد' || occupants === 'None') {
        alert(currentLang === 'ar' ? 'لا يوجد حاجزين في هذه الغرفة' : 'No occupants in this room');
        return;
    }

    let message = currentLang === 'ar' ?
        `الحاجزين في الغرفة ${roomNumber}:\n\n${occupants}` :
        `Occupants in Room ${roomNumber}:\n\n${occupants}`;

    alert(message);
}

// Save room information
async function saveRoomInfo(roomId) {
    const currentLang = localStorage.getItem('adminLang') || 'ar';

    // Get all editable cells for this room
    const roomNumberCell = document.querySelector(`td[data-room-id="${roomId}"][data-field="room_number"]`);
    const roomTypeCell = document.querySelector(`td[data-room-id="${roomId}"][data-field="room_type"]`);
    const priceCell = document.querySelector(`td[data-room-id="${roomId}"][data-field="price"]`);

    const data = {
    room_id: roomId,
    room_number: roomNumberCell.textContent.trim(),
    room_type: roomTypeCell.textContent.trim(),
    price: parseFloat(priceCell.textContent.trim()) // التأكد من إرسال السعر كرقم
};

    if (!roomNumberCell || !roomTypeCell || !priceCell) {
        showNotification(currentLang === 'ar' ? 'خطأ في العثور على بيانات الغرفة' : 'Error finding room data', 'error');
        return;
    }

    const roomNumber = roomNumberCell.textContent.trim();
    const roomTypeText = roomTypeCell.textContent.trim();
    const price = parseFloat(priceCell.textContent.trim());

    // Convert Arabic room type to English
    let roomType = roomTypeText;
    if (roomTypeText === 'فردية') roomType = 'single';
    else if (roomTypeText === 'ثنائية') roomType = 'double';
    else if (roomTypeText === 'ثلاثية') roomType = 'triple';

    if (isNaN(price) || price < 0) {
        showNotification(currentLang === 'ar' ? 'الرجاء إدخال سعر صحيح' : 'Please enter a valid price', 'error');
        return;
    }

    try {
        const response = await fetch('api/owner/update_room.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                room_id: roomId,
                room_number: roomNumber,
                room_type: roomType,
                price: price
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification(currentLang === 'ar' ? 'تم تحديث معلومات الغرفة بنجاح' : 'Room information updated successfully', 'success');
            loadRoomPrices(); // Reload to show updated data
        } else {
            showNotification(data.message || (currentLang === 'ar' ? 'خطأ في الحفظ' : 'Error saving'), 'error');
        }
    } catch (error) {
        console.error('Error saving room info:', error);
        showNotification(currentLang === 'ar' ? 'خطأ في الاتصال بالخادم' : 'Server connection error', 'error');
    }
}

async function saveSingleRoomPrice(roomType) {
    const cell = document.querySelector(`td[data-room-type="${roomType}"]`);
    const price = parseFloat(cell.textContent.trim());

    if (isNaN(price) || price < 0) {
        showNotification('الرجاء إدخال سعر صحيح', 'error');
        return;
    }

    try {
        const response = await fetch('api/owner/update_room_prices.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: dormId,
                prices: [{
                    room_type: roomType,
                    price: price
                }]
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم تحديث السعر بنجاح', 'success');
        } else {
            showNotification(data.message || 'خطأ في الحفظ', 'error');
        }
    } catch (error) {
        console.error('Error saving room price:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

async function saveRoomPrices() {
    const cells = document.querySelectorAll('#roomPricesTableBody td[contenteditable="true"]');
    const prices = [];

    cells.forEach(cell => {
        const roomType = cell.getAttribute('data-room-type');
        const price = parseFloat(cell.textContent.trim());

        if (!isNaN(price) && price >= 0) {
            prices.push({
                room_type: roomType,
                price: price
            });
        }
    });

    try {
        const response = await fetch('api/owner/update_room_prices.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                dorm_id: dormId,
                prices: prices
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم تحديث جميع الأسعار بنجاح', 'success');
            loadRoomPrices(); // Reload
        } else {
            showNotification(data.message || 'خطأ في الحفظ', 'error');
        }
    } catch (error) {
        console.error('Error saving room prices:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}


// ============================================
// Bookings Management
// ============================================

async function loadBookings() {
    try {
        const response = await fetch(`api/owner/get_bookings.php?dorm_id=${dormId}`);
        const data = await response.json();

        if (data.success) {
            allBookings = data.bookings;
            displayBookings(data.bookings);
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
    }
}

function displayBookings(bookings) {
    const tbody = document.getElementById('bookingsTableBody');
    if (!tbody) return;

    if (bookings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 30px;">لا توجد حجوزات</td></tr>';
        return;
    }

    const currentLang = localStorage.getItem('adminLang') || 'ar';

    let html = '';
    bookings.forEach(booking => {
        const statusClass = booking.status === 'Pending' ? 'pending' : booking.status === 'Confirmed' ? 'confirmed' : 'cancelled';
        const statusText = booking.status === 'Pending' ? 'معلق' : booking.status === 'Confirmed' ? 'مؤكد' : 'ملغي';

        html += `<tr>
            <td>${booking.booking_id || '-'}</td>
            <td>${booking.student_name || '-'}</td>
            <td>${booking.student_email || '-'}</td>
            <td>${booking.student_phone || '-'}</td>
            <td>${booking.room_type || '-'}</td>
            <td>${booking.booking_date || '-'}</td>
            <td contenteditable="true" class="editable-cell" data-booking-id="${booking.booking_id}" data-field="total_price" style="background-color: #fffacd;">${booking.total_price || 0} JOD</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <button class="btn btn-sm btn-info" onclick="viewBookingDetails(${booking.booking_id})">
                        <i class="fas fa-eye"></i> ${currentLang === 'ar' ? 'عرض' : 'View'}
                    </button>
                    ${booking.status === 'Pending' ? `
                        <button class="btn btn-sm btn-success" onclick="approveBooking(${booking.booking_id})">
                            <i class="fas fa-check"></i> ${currentLang === 'ar' ? 'موافقة' : 'Approve'}
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="rejectBooking(${booking.booking_id})">
                            <i class="fas fa-times"></i> ${currentLang === 'ar' ? 'رفض' : 'Reject'}
                        </button>
                    ` : ''}
                    <button class="btn btn-sm btn-warning" onclick="saveBookingPrice(${booking.booking_id})">
                        <i class="fas fa-save"></i> ${currentLang === 'ar' ? 'حفظ المبلغ' : 'Save Price'}
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="sendMessageToStudent('${booking.student_email}', '${booking.student_name}')">
                        <i class="fas fa-envelope"></i> ${currentLang === 'ar' ? 'رسالة' : 'Message'}
                    </button>
                </div>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
    switchLanguage(localStorage.getItem('adminLang') || 'ar');
}

function filterBookings() {
    const search = document.getElementById('bookingSearch').value.toLowerCase();
    const status = document.getElementById('bookingStatusFilter').value;

    let filtered = allBookings;

    if (search) {
        filtered = filtered.filter(b =>
            (b.student_name && b.student_name.toLowerCase().includes(search)) ||
            (b.booking_id && b.booking_id.toString().includes(search)) ||
            (b.student_email && b.student_email.toLowerCase().includes(search))
        );
    }

    if (status) {
        filtered = filtered.filter(b => b.status === status);
    }

    displayBookings(filtered);
    switchLanguage(localStorage.getItem('adminLang') || 'ar');
}

async function approveBooking(bookingId) {
    if (!confirm('هل أنت متأكد من الموافقة على هذا الحجز؟')) return;

    try {
        const response = await fetch('api/owner/update_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_id: bookingId,
                status: 'Confirmed',
                action: 'approve'
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم تأكيد الحجز بنجاح', 'success');
            loadBookings();
        } else {
            showNotification(data.message || 'خطأ في تأكيد الحجز', 'error');
        }
    } catch (error) {
        console.error('Error approving booking:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

async function rejectBooking(bookingId) {
    if (!confirm('هل أنت متأكد من رفض هذا الحجز؟')) return;

    try {
        const response = await fetch('api/owner/update_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_id: bookingId,
                status: 'Cancelled',
                action: 'reject'
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم رفض الحجز', 'success');
            loadBookings();
        } else {
            showNotification(data.message || 'خطأ في رفض الحجز', 'error');
        }
    } catch (error) {
        console.error('Error rejecting booking:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

async function saveBookingPrice(bookingId) {
    const currentLang = localStorage.getItem('adminLang') || 'ar';
    const cell = document.querySelector(`td[data-booking-id="${bookingId}"][data-field="total_price"]`);

    if (!cell) {
        showNotification(currentLang === 'ar' ? 'خطأ في العثور على الخلية' : 'Error finding cell', 'error');
        return;
    }

    // Extract price value (remove JOD symbol and spaces)
    const priceText = cell.textContent.trim().replace('JOD', '').trim();
    const newPrice = parseFloat(priceText);

    if (isNaN(newPrice) || newPrice < 0) {
        showNotification(currentLang === 'ar' ? 'الرجاء إدخال مبلغ صحيح' : 'Please enter a valid amount', 'error');
        return;
    }

    try {
        const response = await fetch('api/owner/update_booking_price.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_id: bookingId,
                total_price: newPrice
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification(currentLang === 'ar' ? 'تم حفظ المبلغ بنجاح' : 'Price saved successfully', 'success');
            loadBookings();
        } else {
            showNotification(data.message || (currentLang === 'ar' ? 'خطأ في حفظ المبلغ' : 'Error saving price'), 'error');
        }
    } catch (error) {
        console.error('Error saving booking price:', error);
        showNotification(currentLang === 'ar' ? 'خطأ في الاتصال بالخادم' : 'Server connection error', 'error');
    }
}

async function viewBookingDetails(bookingId) {
    const currentLang = localStorage.getItem('adminLang') || 'ar';

    try {
        const response = await fetch(`api/owner/get_booking_details.php?booking_id=${bookingId}`);
        const data = await response.json();

        if (data.success) {
            const b = data.booking;

            let details = currentLang === 'ar' ?
                `تفاصيل الحجز الكاملة\n\n` :
                `Complete Booking Details\n\n`;

            details += currentLang === 'ar' ?
                `━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n` +
                `معلومات الحجز:\n` +
                `رقم الحجز: ${b.booking_id || '-'}\n` +
                `تاريخ الحجز: ${b.booking_date || '-'}\n` +
                `الحالة: ${b.status || '-'}\n` +
                `المبلغ الإجمالي: ${b.total_price || 0} JOD\n\n` +
                `معلومات الطالب:\n` +
                `الاسم: ${b.student_name || '-'}\n` +
                `البريد الإلكتروني: ${b.student_email || '-'}\n` +
                `رقم الهاتف: ${b.student_phone || '-'}\n` +
                `رقم الهوية: ${b.student_national_id || '-'}\n` +
                `الجنس: ${b.student_gender || '-'}\n` +
                `التخصص: ${b.student_specialty || '-'}\n\n` +
                `معلومات الغرفة:\n` +
                `رقم الغرفة: ${b.room_number || '-'}\n` +
                `نوع الغرفة: ${b.room_type || '-'}\n` +
                `سعر الغرفة: ${b.room_price || 0} JOD/شهر\n\n` +
                `معلومات السكن:\n` +
                `اسم السكن: ${b.dorm_name || '-'}\n` +
                `الموقع: ${b.dorm_location || '-'}\n` +
                `━━━━━━━━━━━━━━━━━━━━━━━━━━━━` :
                `━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n` +
                `Booking Information:\n` +
                `Booking ID: ${b.booking_id || '-'}\n` +
                `Booking Date: ${b.booking_date || '-'}\n` +
                `Status: ${b.status || '-'}\n` +
                `Total Amount: ${b.total_price || 0} JOD\n\n` +
                `Student Information:\n` +
                `Name: ${b.student_name || '-'}\n` +
                `Email: ${b.student_email || '-'}\n` +
                `Phone: ${b.student_phone || '-'}\n` +
                `National ID: ${b.student_national_id || '-'}\n` +
                `Gender: ${b.student_gender || '-'}\n` +
                `Specialty: ${b.student_specialty || '-'}\n\n` +
                `Room Information:\n` +
                `Room Number: ${b.room_number || '-'}\n` +
                `Room Type: ${b.room_type || '-'}\n` +
                `Room Price: ${b.room_price || 0} JOD/month\n\n` +
                `Dorm Information:\n` +
                `Dorm Name: ${b.dorm_name || '-'}\n` +
                `Location: ${b.dorm_location || '-'}\n` +
                `━━━━━━━━━━━━━━━━━━━━━━━━━━━━`;

            alert(details);
        } else {
            showNotification(data.message || (currentLang === 'ar' ? 'خطأ في جلب التفاصيل' : 'Error loading details'), 'error');
        }
    } catch (error) {
        console.error('Error loading booking details:', error);
        showNotification(currentLang === 'ar' ? 'خطأ في الاتصال بالخادم' : 'Server connection error', 'error');
    }
}

async function sendMessageToStudent(studentEmail, studentName) {
    const currentLang = localStorage.getItem('adminLang') || 'ar';

    const subject = prompt(currentLang === 'ar' ? 'عنوان الرسالة:' : 'Message Subject:',
                          currentLang === 'ar' ? 'بخصوص حجزك' : 'Regarding your booking');
    if (!subject) return;

    const message = prompt(currentLang === 'ar' ? 'نص الرسالة:' : 'Message Text:', '');
    if (!message) return;

    try {
        const response = await fetch('api/owner/send_message_to_student.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                student_email: studentEmail,
                subject: subject,
                message: message
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification(currentLang === 'ar' ? 'تم إرسال الرسالة بنجاح' : 'Message sent successfully', 'success');
        } else {
            showNotification(data.message || (currentLang === 'ar' ? 'خطأ في الإرسال' : 'Error sending message'), 'error');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        showNotification(currentLang === 'ar' ? 'خطأ في الاتصال بالخادم' : 'Server connection error', 'error');
    }
}


// ============================================
// Comments Management
// ============================================

async function loadComments() {
    try {
        const response = await fetch(`api/owner/get_comments.php?dorm_id=${dormId}`);
        const data = await response.json();

        console.log('Comments Response:', data);

        if (data.success) {
            allComments = data.comments;
            displayComments(data.comments);
        } else {
            console.error('Failed to load comments:', data.message);
            const tbody = document.getElementById('commentsTableBody');
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">${data.message || 'خطأ في تحميل التعليقات'}</td></tr>`;
            }
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        const tbody = document.getElementById('commentsTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">خطأ في الاتصال بالخادم</td></tr>`;
        }
    }
}

function displayComments(comments) {
    const tbody = document.getElementById('commentsTableBody');
    if (!tbody) return;

    if (comments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 30px;">لا توجد تعليقات</td></tr>';
        return;
    }

    const currentLang = localStorage.getItem('adminLang') || 'ar';

    let html = '';
    comments.forEach(comment => {
        const commentId = comment.id || comment.comment_id;
        const stars = '⭐'.repeat(comment.rating || comment.number_of_stars || 0);

        html += `<tr>
            <td>${comment.name || comment.user_name || 'غير معروف'}</td>
            <td>${stars} (${comment.rating || comment.number_of_stars || 0})</td>
            <td>${comment.comment || comment.comment_text || ''}</td>
            <td>${comment.created_at || '-'}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteComment(${commentId})">
                    <i class="fas fa-trash"></i> ${currentLang === 'ar' ? 'حذف' : 'Delete'}
                </button>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
}

function filterComments() {
    const rating = document.getElementById('commentRatingFilter').value;

    let filtered = allComments;

    if (rating) {
        filtered = filtered.filter(c =>
            (c.rating && c.rating == rating) ||
            (c.number_of_stars && c.number_of_stars == rating)
        );
    }

    displayComments(filtered);
}

async function replyToComment(commentId) {
    const reply = prompt('اكتب ردك على التعليق:');
    if (!reply) return;

    try {
        const response = await fetch('api/owner/reply_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                comment_id: commentId,
                reply: reply
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم إرسال الرد بنجاح', 'success');
            loadComments();
        } else {
            showNotification(data.message || 'خطأ في إرسال الرد', 'error');
        }
    } catch (error) {
        console.error('Error replying to comment:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

async function deleteComment(commentId) {
    if (!confirm('هل أنت متأكد من حذف هذا التعليق؟')) return;

    try {
        const response = await fetch('api/owner/delete_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                comment_id: commentId
            })
        });

        const data = await response.json();
        if (data.success) {
            showNotification('تم حذف التعليق بنجاح', 'success');
            loadComments();
        } else {
            showNotification(data.message || 'خطأ في حذف التعليق', 'error');
        }
    } catch (error) {
        console.error('Error deleting comment:', error);
        showNotification('خطأ في الاتصال بالخادم', 'error');
    }
}

// ============================================
// Notifications Management
// ============================================

async function loadNotifications() {
    try {
        const response = await fetch(`api/owner/get_notifications.php?owner_id=${ownerId}`);
        const data = await response.json();

        if (data.success) {
            allNotifications = data.notifications;
            
            // تحديث الجدول في قسم الإشعارات إذا كان موجوداً
            const tbody = document.getElementById('notificationsTableBody');
            if (tbody) {
                displayNotifications(data); 
            }
            
            // تحديث الرقم الأحمر فوق الجرس (البادج)
            const badge = document.getElementById('unreadCountBadge'); // تأكدي أن الـ ID مطابق لما في الـ HTML
            if (badge) {
                const count = data.unread_count || 0;
                badge.innerText = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            }
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}
function displayNotifications(data) {
    // 1. تحديد العناصر في الصفحة
    const tbody = document.getElementById('notificationsTableBody');
    const badge = document.getElementById('topbarNotificationCount');
    
    // 2. تحديث الرقم فوق الجرس (البادج)
    if (badge) {
        const count = data.unread_count || 0;
        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    // 3. التحقق من وجود جدول العرض
    if (!tbody) return;

    // 4. التأكد من وجود مصفوفة الإشعارات (استخدمنا data.notifications حسب الـ PHP)
    const notificationsList = data.notifications || [];

    if (notificationsList.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 20px;">لا توجد رسائل أو تنبيهات حالياً</td></tr>';
        return;
    }

    // 5. رسم جدول الإشعارات
    let html = '';
    notificationsList.forEach(item => {
        const isUnread = item.is_read == 0;
        const rowStyle = isUnread ? 'font-weight: bold; background-color: #f8fafc;' : '';
        
        html += `
            <tr style="${rowStyle}">
                <td>${escapeHtml(item.sender_name || 'النظام')}</td>
                <td>${escapeHtml(item.subject || 'بدون عنوان')}</td>
                <td>${escapeHtml(item.message || '')}</td>
                <td>${escapeHtml(item.created_at || '')}</td>
                <td>
                    <div style="display:flex; gap:5px;">
                        ${isUnread ? `<button class="btn btn-sm btn-success" onclick="markAsRead(${item.id})" title="تحديد كمقروء"><i class="fas fa-check"></i></button>` : ''}
                        <button class="btn btn-sm btn-primary" onclick="replyToNotification(${item.id}, '${item.sender_email || ''}')" title="رد">
                            <i class="fas fa-reply"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
    });

    tbody.innerHTML = html;
}

function updateNotificationBadge(count) {
    const topbarBadge = document.getElementById('topbarNotificationCount');
    if (topbarBadge) {
        if (count > 0) {
            topbarBadge.textContent = count;
            topbarBadge.style.display = 'inline-block'; // إظهاره إذا كان هناك إشعارات
        } else {
            topbarBadge.style.display = 'none'; // إخفاؤه إذا كان صفر
        }
    }
}

async function markAsRead(notificationId) {
    try {
        const response = await fetch('api/owner/mark_notification_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                notification_id: notificationId
            })
        });

        const data = await response.json();
        if (data.success) {
            loadNotifications();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

async function replyToNotification(notificationId, senderEmail) {
    // التأكد من وجود إيميل للرد عليه
    if (!senderEmail || senderEmail === 'undefined' || senderEmail === 'null') {
        alert('هذا إشعار تلقائي من النظام، لا يمكنك الرد عليه مباشرة.');
        return;
    }

    const subject = prompt('موضوع الرسالة:');
    if (!subject) return;

    const message = prompt('نص الرسالة:');
    if (!message) return;

    try {
        const response = await fetch('api/owner/send_reply.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                recipient_email: senderEmail,
                subject: subject,
                message: message
            })
        });

        const data = await response.json();
        if (data.success) {
            alert('تم إرسال الرد بنجاح');
            // إذا كان لديك دالة لتحديث الحالة، استدعها هنا
            if (typeof markAsRead === 'function') {
                markAsRead(notificationId);
            }
            loadNotifications(); // إعادة تحميل الإشعارات لتحديث الجدول
        } else {
            alert(data.message || 'خطأ في إرسال الرد');
        }
    } catch (error) {
        console.error('Error sending reply:', error);
        alert('خطأ في الاتصال بالخادم');
    }
}

// ============================================
// Utility Functions
// ============================================

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

async function showOwnerNotifications() {
    const overlayId = 'ownerNotifOverlay';
    let overlay = document.getElementById(overlayId);
    
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = overlayId;
        document.body.appendChild(overlay);
    }

    // تنسيق النافذة
    overlay.style.cssText = "display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:99999; justify-content:center; align-items:center; direction:rtl;";

    overlay.innerHTML = `
        <div style="background:white; width:95%; max-width:550px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); display:flex; flex-direction:column; max-height:85vh;">
            <div style="padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; color:#1e3a8a;">🔔 الرسائل والإشعارات الواردة</h3>
                <button onclick="document.getElementById('${overlayId}').style.display='none'" style="background:none; border:none; font-size:35px; cursor:pointer; color:#999;">&times;</button>
            </div>
            <div id="popupNotifBody" style="padding:20px; overflow-y:auto; flex:1; background:#fff;">
                <p style="text-align:center;">جاري تحميل الرسائل...</p>
            </div>
        </div>`;

    overlay.style.display = 'flex';
    const body = document.getElementById('popupNotifBody');

    try {
        const response = await fetch(`api/owner/get_notifications.php?owner_id=${ownerId}`);
        const data = await response.json();

        if (data.success && data.notifications.length > 0) {
            let html = '';
            data.notifications.forEach(n => {
                const isUnread = n.is_read == 0;
                const bg = isUnread ? '#f0f7ff' : '#fafafa';
                html += `
                    <div style="border:1px solid #eee; padding:15px; margin-bottom:15px; border-radius:12px; background:${bg}; text-align:right; border-right: 5px solid ${isUnread ? '#3b82f6' : '#ccc'};">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <strong style="color:#1e3a8a;">${escapeHtml(n.sender_name || 'النظام')}</strong>
                            <small style="color:#888;">${n.created_at}</small>
                        </div>
                        <div style="background:white; padding:12px; border:1px solid #efefef; border-radius:8px;">
                            <strong style="display:block; margin-bottom:5px;">${escapeHtml(n.subject)}</strong>
                            <p style="margin:0; line-height:1.5; color:#444;">${escapeHtml(n.message)}</p>
                        </div>
                        <div style="margin-top:12px; display:flex; gap:10px;">
                            ${n.type === 'message' ? `
                                <button onclick="handlePopupReply('${n.sender_email}', '${n.id}')" style="background:#10b981; color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer; font-weight:bold; font-size:13px;">إرسال رد</button>
                            ` : ''}
                            ${isUnread ? `
                                <button onclick="markAsRead(${n.id}); showOwnerNotifications();" style="background:#3b82f6; color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer; font-size:13px;">تحديد كمقروء</button>
                            ` : ''}
                        </div>
                    </div>`;
            });
            body.innerHTML = html;
        } else {
            body.innerHTML = '<p style="text-align:center; padding:40px;">لا توجد رسائل حالياً.</p>';
        }
    } catch (e) {
        body.innerHTML = '<p style="color:red; text-align:center;">خطأ في الاتصال بالسيرفر.</p>';
    }
}

// دالة مساعدة للرد من داخل النافذة المنبثقة
function handlePopupReply(email, id) {
    document.getElementById('ownerNotifOverlay').style.display = 'none';
    if (typeof replyToNotification === 'function') {
        replyToNotification(id, email);
    }
}

