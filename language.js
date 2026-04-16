// Language Support for Admin Dashboard
const translations = {
    ar: {
        // Sidebar
        'admin_panel': 'لوحة المدير',
        'dashboard': 'الرئيسية',
        'dorms_management': 'إدارة السكنات',
        'owners_management': 'إدارة الملاك',
        'students_management': 'إدارة الطلاب',
        'comments_management': 'إدارة التعليقات',
        'messages': 'الرسائل',
        'home_page': 'الموقع الرئيسي',
        'logout': 'تسجيل الخروج',

        // Dashboard
        'dashboard_title': 'لوحة التحكم الرئيسية',
        'dashboard_subtitle': 'نظرة عامة على النظام',
        'total_students': 'إجمالي الطلاب',
        'total_dorms': 'إجمالي السكنات',
        'total_bookings': 'إجمالي الحجوزات',
        'total_owners': 'إجمالي الملاك',
        'total_revenue': 'إجمالي الإيرادات',
        'total_comments': 'إجمالي التعليقات',

        // Dorms
        'dorms_title': 'إدارة السكنات',
        'dorms_subtitle': 'عرض وتعديل جميع بيانات السكنات',
        'dorms_table_title': 'جدول السكنات الكامل',
        'add_new_row': 'إضافة صف جديد',
        'save_all_changes': 'حفظ جميع التغييرات',
        'search_dorm': 'ابحث عن سكن...',
        'actions': 'الإجراءات',

        // Owners
        'owners_title': 'إدارة الملاك',
        'owners_subtitle': 'تعديل معلومات ملاك السكنات',
        'owners_list': 'قائمة الملاك',
        'owner_id': 'المعرف',
        'owner_name': 'الاسم',
        'owner_email': 'البريد الإلكتروني',
        'owner_phone': 'الهاتف',
        'dorm_name': 'اسم السكن',
        'save': 'حفظ',

        // Students
        'students_title': 'إدارة الطلاب',
        'students_subtitle': 'عرض وإدارة حسابات الطلاب',
        'students_list': 'قائمة الطلاب',
        'student_id': 'المعرف',
        'student_name': 'الاسم',
        'student_email': 'البريد الإلكتروني',
        'student_phone': 'الهاتف',
        'university': 'الجامعة',
        'bookings': 'الحجوزات',
        'search_student': 'ابحث عن طالب...',

        // Comments
        'comments_title': 'إدارة التعليقات',
        'comments_subtitle': 'عرض جميع التعليقات من الموقع الرئيسي والسكنات',
        'comments_list': 'قائمة التعليقات',
        'comment_id': 'المعرف',
        'commenter_name': 'اسم المعلق',
        'commenter_email': 'البريد الإلكتروني',
        'rating': 'التقييم',
        'comment': 'التعليق',
        'date': 'التاريخ',
        'reply': 'رد',
        'delete': 'حذف',

        // Messages
        'messages_title': 'نظام الرسائل',
        'messages_subtitle': 'إرسال رسائل للمستخدمين',
        'send_new_message': 'إرسال رسالة جديدة',
        'recipient_type': 'نوع المستلم:',
        'choose_recipient_type': 'اختر نوع المستلم',
        'student': 'طالب',
        'owner': 'مالك',
        'all_students': 'جميع الطلاب',
        'all_owners': 'جميع الملاك',
        'recipient': 'المستلم:',
        'choose_recipient': 'اختر المستلم',
        'subject': 'الموضوع:',
        'message': 'الرسالة:',
        'send_message': 'إرسال الرسالة',

        // Common
        'welcome': 'مرحباً، المدير',
        'no_data': 'لا يوجد',
        'unknown': 'غير معروف',
        'main_page': 'الصفحة الرئيسية',

        // Owner Dashboard
        'owner_dashboard_title': 'لوحة تحكم المالك - نظام حجز السكنات',
        'owner_panel': 'لوحة المالك',
        'dorm_management': 'إدارة السكن',
        'dorm_management_desc': 'تعديل معلومات السكن الخاص بك',
        'dorm_info': 'معلومات السكن',
        'facilities': 'المرافق والخدمات',
        'facility_name': 'اسم المرفق',
        'available': 'متوفر',
        'save_facilities': 'حفظ المرافق',
        'field': 'الحقل',
        'value': 'القيمة',
        'rating': 'التقييم',
        'comment': 'التعليق',
        'room_prices': 'أسعار الغرف',
        'room_prices_desc': 'عرض وتعديل معلومات الغرف وأسعارها والطلاب المحجوزين',
        'room_id': 'رقم الغرفة (ID)',
        'room_number': 'رقم الغرفة',
        'room_type': 'نوع الغرفة',
        'price': 'السعر (دينار/شهر)',
        'monthly_rent': 'الإيجار الشهري (دينار)',
        'booked_by': 'محجوزة من قبل',
        'capacity': 'السعة',
        'current_occupancy': 'الإشغال الحالي',
        'available_spots': 'الأماكن المتاحة',
        'save_prices': 'حفظ الأسعار',
        'save_all_changes': 'حفظ جميع التغييرات',

        // Bookings
        'bookings': 'الحجوزات',
        'bookings_management': 'إدارة الحجوزات',
        'bookings_management_desc': 'عرض وإدارة جميع الحجوزات',
        'booking_id': 'رقم الحجز',
        'room_type': 'نوع الغرفة',
        'booking_date': 'تاريخ الحجز',
        'amount': 'المبلغ',
        'status': 'الحالة',
        'all_statuses': 'جميع الحالات',
        'pending': 'معلق',
        'confirmed': 'مؤكد',
        'cancelled': 'ملغي',

        // Comments
        'comments': 'التعليقات',
        'comments_management': 'إدارة التعليقات',
        'comments_management_desc': 'إدارة تعليقات الطلاب على السكن',
        'all_ratings': 'جميع التقييمات',
        'stars': 'نجوم',
        'star': 'نجمة',
        'commenter_name': 'اسم المعلق',
        'commenter_email': 'البريد الإلكتروني',

        // Notifications
        'notifications': 'الإشعارات',
        'notifications_desc': 'عرض الرسائل والإشعارات',
        'sender': 'المرسل',
        'subject': 'الموضوع',
        'message': 'الرسالة',
        'date': 'التاريخ',
        'actions': 'الإجراءات',

        // Common
        'back_to_site': 'العودة للموقع',
        'welcome': 'مرحباً',
        'student_name': 'اسم الطالب',
        'student_email': 'البريد الإلكتروني',
        'student_phone': 'رقم الهاتف',

        'dashboard_overview': 'نظرة عامة على سكنك',
    'total_rooms': 'إجمالي الغرف',
    'available_rooms': 'الغرف المتاحة',
    'pending_bookings': 'الحجوزات المعلقة',
    'avg_rating': 'متوسط التقييم',
    'dorm_name': 'اسم السكن',
    'location': 'الموقع',
    'gender': 'النوع',
    'status': 'الحالة',
    'available_facilities': 'المرافق المتاحة',
    'loading': 'جاري التحميل...',
    },
    en: {
        // Sidebar
        'admin_panel': 'Admin Panel',
        'dashboard': 'Dashboard',
        'dorms_management': 'Dorms Management',
        'owners_management': 'Owners Management',
        'students_management': 'Students Management',
        'comments_management': 'Comments Management',
        'messages': 'Messages',
        'home_page': 'Home Page',
        'logout': 'Logout',

        // Dashboard
        'dashboard_title': 'Main Dashboard',
        'dashboard_subtitle': 'System Overview',
        'total_students': 'Total Students',
        'total_dorms': 'Total Dorms',
        'total_bookings': 'Total Bookings',
        'total_owners': 'Total Owners',
        'total_revenue': 'Total Revenue',
        'total_comments': 'Total Comments',

        // Dorms
        'dorms_title': 'Dorms Management',
        'dorms_subtitle': 'View and edit all dorms data',
        'dorms_table_title': 'Complete Dorms Table',
        'add_new_row': 'Add New Row',
        'save_all_changes': 'Save All Changes',
        'search_dorm': 'Search for dorm...',
        'actions': 'Actions',

        // Owners
        'owners_title': 'Owners Management',
        'owners_subtitle': 'Edit dorm owners information',
        'owners_list': 'Owners List',
        'owner_id': 'ID',
        'owner_name': 'Name',
        'owner_email': 'Email',
        'owner_phone': 'Phone',
        'dorm_name': 'Dorm Name',
        'save': 'Save',

        // Students
        'students_title': 'Students Management',
        'students_subtitle': 'View and manage student accounts',
        'students_list': 'Students List',
        'student_id': 'ID',
        'student_name': 'Name',
        'student_email': 'Email',
        'student_phone': 'Phone',
        'university': 'University',
        'bookings': 'Bookings',
        'search_student': 'Search for student...',

        // Comments
        'comments_title': 'Comments Management',
        'comments_subtitle': 'View all comments from home page and dorms',
        'comments_list': 'Comments List',
        'comment_id': 'ID',
        'commenter_name': 'Commenter Name',
        'commenter_email': 'Email',
        'rating': 'Rating',
        'comment': 'Comment',
        'date': 'Date',
        'reply': 'Reply',
        'delete': 'Delete',

        // Messages
        'messages_title': 'Messaging System',
        'messages_subtitle': 'Send messages to users',
        'send_new_message': 'Send New Message',
        'recipient_type': 'Recipient Type:',
        'choose_recipient_type': 'Choose recipient type',
        'student': 'Student',
        'owner': 'Owner',
        'all_students': 'All Students',
        'all_owners': 'All Owners',
        'recipient': 'Recipient:',
        'choose_recipient': 'Choose recipient',
        'subject': 'Subject:',
        'message': 'Message:',
        'send_message': 'Send Message',

        // Common
        'welcome': 'Welcome, Admin',
        'no_data': 'None',
        'unknown': 'Unknown',
        'main_page': 'Home Page',

        // Owner Dashboard
        'owner_dashboard_title': 'Owner Dashboard - Dorm Booking System',
        'owner_panel': 'Owner Panel',
        'dorm_management': 'Dorm Management',
        'dorm_management_desc': 'Edit your dorm information',
        'dorm_info': 'Dorm Information',
        'facilities': 'Facilities & Services',
        'facility_name': 'Facility Name',
        'available': 'Available',
        'save_facilities': 'Save Facilities',
        'field': 'Field',
        'value': 'Value',
        'rating': 'Rating',
        'comment': 'Comment',
        'room_prices': 'Room Prices',
        'room_prices_desc': 'View and edit room information, prices, and booked students',
        'room_id': 'Room ID',
        'room_number': 'Room Number',
        'room_type': 'Room Type',
        'price': 'Price (JOD/month)',
        'monthly_rent': 'Monthly Rent (JOD)',
        'booked_by': 'Booked By',
        'capacity': 'Capacity',
        'current_occupancy': 'Current Occupancy',
        'available_spots': 'Available Spots',
        'save_prices': 'Save Prices',
        'save_all_changes': 'Save All Changes',

        // Bookings
        'bookings': 'Bookings',
        'bookings_management': 'Bookings Management',
        'bookings_management_desc': 'View and manage all bookings',
        'booking_id': 'Booking ID',
        'room_type': 'Room Type',
        'booking_date': 'Booking Date',
        'amount': 'Amount',
        'status': 'Status',
        'all_statuses': 'All Statuses',
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'cancelled': 'Cancelled',

        // Comments
        'comments': 'Comments',
        'comments_management': 'Comments Management',
        'comments_management_desc': 'Manage student comments on dorm',
        'all_ratings': 'All Ratings',
        'stars': 'Stars',
        'star': 'Star',
        'commenter_name': 'Commenter Name',
        'commenter_email': 'Email',

        // Notifications
        'notifications': 'Notifications',
        'notifications_desc': 'View messages and notifications',
        'sender': 'Sender',
        'subject': 'Subject',
        'message': 'Message',
        'date': 'Date',
        'actions': 'Actions',

        // Common
        'back_to_site': 'Back to Site',
        'welcome': 'Welcome',
        'student_name': 'Student Name',
        'student_email': 'Email',
        'student_phone': 'Phone',

        'dashboard_overview': 'Overview of your dorm',
    'total_rooms': 'Total Rooms',
    'available_rooms': 'Available Rooms',
    'pending_bookings': 'Pending Bookings',
    'avg_rating': 'Average Rating',
    'dorm_name': 'Dorm Name',
    'location': 'Location',
    'gender': 'Gender',
    'status': 'Status',
    'available_facilities': 'Available Facilities',
    'loading': 'Loading...',
    }
};

// let currentLang = 'ar';

function switchLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('adminLang', lang);

    // Update HTML direction
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');

    // Update all translatable elements (support both data-lang and language-switch)
    document.querySelectorAll('[data-lang], [language-switch]').forEach(element => {
        const key = element.getAttribute('data-lang') || element.getAttribute('language-switch');
        if (translations[lang][key]) {
            // Check if it's a title or regular element
            if (element.tagName === 'TITLE') {
                element.textContent = translations[lang][key];
            } else {
                element.textContent = translations[lang][key];
            }
        }
    });

    // Update placeholders
    document.querySelectorAll('[data-lang-placeholder]').forEach(element => {
        const key = element.getAttribute('data-lang-placeholder');
        if (translations[lang][key]) {
            element.setAttribute('placeholder', translations[lang][key]);
        }
    });
}

// Initialize language on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedLang = localStorage.getItem('adminLang') || 'ar';
    switchLanguage(savedLang);
});



