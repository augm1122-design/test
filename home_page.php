<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    <!-- يحدد ترميز الأحرف لدعم مجموعة واسعة من اللغات -->
    <!-- يضمن أن الموقع متجاوب ويعمل بشكل جيد على جميع الأجهزة. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- يحدد عنوان الصفحة الذي يظهر في علامة تبويب المتصفح -->
    <title>MU-DORMS - Find Your Perfect Dorm</title>
    <!-- هذه الأسطر تربط بملفات خارجية. أول سطرين يربطان بخطوط Google Fonts 
     لاستخدام خط "Poppins"، 
     والثالث يربط بـ Font Awesome للحصول على الأيقونات (مثل أيقونة القلب والمستخدم) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="rtl-support.css">
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    color: #212529;
}
header {
    background-color: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
}
.logo {
    font-weight: 700;
    font-size: 1.4rem;
    color: black;
    text-decoration: none;
}
nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
}
nav ul li a {
    color: #343a40;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}
nav ul li a:hover {
    color: #007bff;
}
.sign-in-btn, .profile-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s ease;
    white-space: nowrap;
}
.sign-in-btn:hover, .profile-btn:hover {
    background-color: #0056b3;
}
.profile-btn {
    background-color: #28a745;
    margin-right: 10px;
}
.profile-btn:hover {
    background-color: #218838;
}
.auth-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}
.favorite-nav-btn {
    color: #010101ff;
    font-size: 1.2rem;
    text-decoration: none;
    margin-left: 20px;
}
        :root {
            --primary-blue: #007bff;
            --primary-color: #007bff;
            --dark-blue: #0056b3;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #343a40;
            --text-color: #495057;
            --white: #ffffff;
            --border-color: #dee2e6;
            --star-color: #ffc107;
            --light-bg-card: #f1f7fe;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: var(--white);
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8em;
            font-weight: 700;
            color: var(--dark-gray);
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-blue);
        }

        .nav-actions {
            display: flex;
            align-items: center;
        }

        .sign-in-btn {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .sign-in-btn:hover {
            background-color: var(--dark-blue);
        }

        .hero {
            background-image: url('https://media.gettyimages.com/id/533694324/photo/homes.jpg?s=612x612&w=0&k=20&c=q_3dl2QdIymjoAS2Rb0rVbICrrb1fNd8cKPCeoGNdKM='); 
            background-size: cover;
            background-position: center;
            color: var(--white);
            text-align: center;
            padding: 100px 20px;
            border-radius: 10px;
            margin-top: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); 
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h2 {
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            font-size: 1.2em;
            margin-bottom: 40px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }

        .why-choose {
            padding: 80px 0;
            text-align: center;
        }

        .why-choose h2 {
            font-size: 2.5em;
            margin-bottom: 50px;
            color: var(--dark-gray);
            font-weight: 700;
        }

        .explore-dorms-btn {
            display: inline-block;
            padding: 18px 45px;
            background: linear-gradient(135deg, #007bff 0%, #efebf2ff 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .explore-dorms-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #007bff 0%, #007bff 100%);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .explore-dorms-btn:hover:before {
            left: 0;
        }

        .explore-dorms-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(10, 32, 129, 0.6);
        }

        .explore-dorms-btn i {
            margin-left: 10px;
            font-size: 1.1em;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            justify-content: center;
        }

        .feature-card {
            background-color: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: left;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .feature-card i {
            font-size: 2.5em;
            color: var(--primary-blue);
            margin-bottom: 20px;
            background-color: var(--light-bg-card);
            padding: 15px;
            border-radius: 50%;
            display: inline-block;
        }

        .feature-card h3 {
            font-size: 1.5em;
            color: var(--dark-gray);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .feature-card p {
            font-size: 1em;
            color: var(--text-color);
        }
        .featured-dorms {
            padding: 60px 0;
        }

        .featured-dorms h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            text-align: center;
            color: var(--dark-gray);
            font-weight: 700;
        }

        .dorm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            justify-content: center;
        }

        .dorm-card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dorm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .dorm-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .dorm-info {
            padding: 20px;
        }

        .dorm-info h3 {
            font-size: 1.4em;
            color: var(--dark-gray);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .dorm-info p {
            font-size: 0.95em;
            color: var(--text-color);
        }

        /* ============ تنسيقات قسم التعليقات ============ */
        .student-feedback {
            padding: 80px 0;
            background-color: var(--light-gray);
        }

        .student-feedback h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            text-align: center;
            color: var(--dark-gray);
            font-weight: 700;
        }

        /* قائمة التعليقات */
        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }1

        .comment-item {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .comment-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            font-weight: 700;
        }

        .author-info h4 {
            margin: 0;
            font-size: 1.1em;
            color: #2c3e50;
        }

        .comment-date {
            color: #999;
            font-size: 0.85em;
        }

        .comment-rating .stars {
            font-size: 1.2em;
            color: #ffc107;
        }

        .comment-body p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }

        .dorm-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
            margin-top: 8px;
        }

        .no-comments {
            text-align: center;
            color: #999;
            padding: 40px;
            font-size: 1.1em;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1em;
        }

        .loading i {
            margin-right: 10px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* زر تحميل المزيد */
        .load-more-container {
            text-align: center;
            margin-top: 30px;
        }

        .load-more-btn {
            background: white;
            color: #007bff;
            border: 2px solid #007bff;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .load-more-btn:hover {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        /* نموذج إضافة تعليق */
        .add-comment-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .add-comment-form h3 {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .char-count {
            display: block;
            text-align: right;
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* تقييم النجوم */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            font-size: 2em;
            color: #ddd;
            transition: color 0.2s ease;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }

        .submit-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
        footer {
            background-color: var(--white);
            padding: 40px 0;
            text-align: center;
            border-top: 1px solid var(--border-color);
            font-size: 0.9em;
            color: #6c757d;
        }

        .footer-links {
            list-style: none;
            margin: 0 0 20px 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .footer-links a {
            text-decoration: none;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-blue);
        }

        @media (max-width: 992px) {
            .navbar {
                flex-wrap: wrap;
                justify-content: center;
            }
            .logo {
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }
            .nav-links {
                width: 100%;
                justify-content: center;
                margin-bottom: 15px;
            }
            .nav-links li {
                margin: 0 15px;
            }
            .nav-actions {
                width: 100%;
                justify-content: center;
            }
            .hero h2 {
                font-size: 2.5em;
            }
            .hero p {
                font-size: 1em;
            }
            .features-grid, .dorm-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 70px 15px;
                margin-top: 20px;
            }
            .hero h2 {
                font-size: 2em;
            }
            .hero p {
                font-size: 0.9em;
            }
            .why-choose, .featured-dorms, .student-feedback, .comment-section {
                padding: 60px 0;
            }
            .why-choose h2, .featured-dorms h2, .student-feedback h2, .comment-section h2 {
                font-size: 2em;
                margin-bottom: 30px;
            }
            .feature-card {
                padding: 25px;
            }
            .feature-card h3 {
                font-size: 1.3em;
            }
            .feature-card i {
                font-size: 2em;
            }
            .feedback-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .reviewer-avatar {
                margin: 0 0 15px 0;
            }
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                flex-direction: column;
                align-items: center;
            }
            .nav-links li {
                margin: 10px 0;
            }
            .hero h2 {
                font-size: 1.8em;
            }
            .hero p {
                font-size: 0.85em;
            }
            .features-grid, .dorm-grid {
                grid-template-columns: 1fr;
            }
            .feature-card h3 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>
    <!-- زر تبديل اللغة -->
    <button id="languageToggle">العربية</button>

    <header>
        <!-- شعار الموقع، وهو أيضًا رابط للصفحة الرئيسية -->
    <a href="home_page.php" class="logo">MU-DORMS</a>
    <!--  قائمة التنقل -->
    <nav>
        <ul class="nav-links">    <!-- عنوان القائمة -->
            <li>            <!-- عناصر القائمة -->
                <a href="favorites.php" class="favorite-nav-btn" data-translate="my_favorites" title="My Favorites">
                <i class="fas fa-heart"></i> <span>My Favorites</span>

                </a>
            </li>
            <li><a href="dorm_type.php" data-translate="dorms">Dorms</a></li>
            <li><a href="about.php" data-translate="about">About</a></li>
            <li><a href="contact.php" data-translate="contact">Contact</a></li>
        </ul>
    </nav>
    <div class="auth-buttons">
        <!-- يتحقق مما إذا كان المستخدم مسجلًا للدخول بواسطة التحقق من وجود user_email -->
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="user_profile.php" class="profile-btn">
                <i class="fas fa-user"></i>
            </a>
            <a href="logout.php" class="sign-in-btn" data-translate="Log out">Log Out</a>
            <!-- إذا لم يكن المستخدم مسجلًا للدخول، يتم تنفيذ هذا الجزء -->
        <?php else: ?>
            <!-- <a href="login_options.php" class="sign-in-btn">Sign In</a> -->
           <a href="log_in_student.php" class="sign-in-btn" data-translate="sign in">Sign In</a>
        <?php endif; ?>
    </div>
</header>
    <main>
        <!-- القسم الترحيبي الرئيسي مع صورة في الخلفية -->
        <section class="hero">
            <div class="hero-content">
                <h2 language-switch="home_hero_title">Find Your Perfect Dorm</h2>
                <p data-translate="home_hero_description">Explore a wide range of dormitories near your university. Discover amenities, compare prices, and read reviews from current residents.</p>
            </div>
        </section>
        <!-- قسم يشرح فوائد استخدام الموقع -->
        <section class="why-choose">
            <div class="container">
                <!-- زر عرض السكنات -->
                <a href="dorm_type.php" class="explore-dorms-btn">
                    <i class="fas fa-building"></i> Explore Our Dorms
                </a>
                <!-- عنوان القسم -->
                <h2 data-translate="why_choose_title">Why Choose MU-DORMS ?</h2>
                <p class="section-description" data-translate="why_choose_description">We make finding the right dorm easy and stress-free.</p>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-search"></i>
                        <h3 data-translate="feature_search_title">Comprehensive Search</h3>
                        <p data-translate="feature_search_description">Search by location, price, amenities, and more to find the perfect match.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-list-alt"></i>
                        <h3 data-translate="feature_listings_title">Detailed Listings</h3>
                        <p data-translate="feature_listings_description">Get all the information you need, including photos, floor plans, and virtual tours.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-star"></i>
                        <h3 data-translate="feature_reviews_title">Verified Reviews</h3>
                        <p data-translate="feature_reviews_description">Read honest reviews from current and former residents to make informed decisions.</p>
                    </div>
                </div>
            </div>
        </section>
       <!-- عنوان القسم -->
        <section class="featured-dorms">
            <div class="container">
                <h2 data-translate="featured_dorms_title">Featured Dorms</h2>
                <!--لعرض بطاقات السكنات  -->
                <div class="dorm-grid">
                    <div class="dorm-card">
                        <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcTIh7mrOEXuuSilG-F8JpmWRAQJ7k2zOLEUrar5b-lECO-Y03gz" alt="Al-Manzel Dorm">
                        <div class="dorm-info">
                            <h3>Al-Manzel Dorm</h3>
                            <p>Charming and intimate living space, providing a cozy and personal environment for students.</p>
                        </div>
                    </div>
                    <div class="dorm-card">
                        <img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQiPFde1XNqs1XZ_rVXGUUuYhHLnllyV5TNAp8gxaV-zsArndZF" alt="Al-Qatawneh Dorm">
                        <div class="dorm-info">
                            <h3>Al-Qatawneh Dorm</h3>
                            <p>Strategic location close to transportation and essential facilities.</p>
                        </div>
                    </div>
                    <div class="dorm-card">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUMsdEuwGfHciNc2WcaJIODO416WYiXW1p3Q&s" alt="Maya Residences">
                        <div class="dorm-info">
                            <h3>Maya Residences</h3>
                            <p>Community-focused living with shared spaces that encourage interaction and collaboration. A lively and engaging environment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- قسم تعليقات الطلاب -->
<section class="student-feedback">
    <div class="container">
        <h2 data-translate="student_feedback_title">Student Feedback</h2>
        
        <!-- Loading -->
        <div class="loading" id="homeCommentsLoading" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> <span data-translate="loading">Loading...</span>
        </div>

        <!-- قائمة التعليقات -->
        <div class="comments-list" id="homeCommentsList">
        </div>

        <!-- زر تحميل المزيد -->
        <div class="load-more-container" id="homeLoadMoreContainer" style="display: none;">
            <button class="load-more-btn" id="homeLoadMoreBtn" data-translate="load_more">Load More Reviews</button>
        </div>
    </div>
</section>
        <!-- قسم كتابة التعليقات -->
        <section class="student-feedback" style="padding-top: 40px;">
            <div class="container">
                <!-- نموذج إضافة تعليق -->
                <div class="add-comment-form">
                    <h3 data-translate="write_review">Write a Review</h3>
                    <form id="commentForm">
                        <div class="form-group">
                            <label for="userName" data-translate="your name">Your Name</label>
                            <input type="text" id="userName" name="name" required maxlength="30" data-translate-placeholder="name_placeholder" placeholder="Enter your name">
                        </div>

                        <div class="form-group">
                            <label data-translate="your rating">Your Rating</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="userComment" data-translate="your review">Your Review</label>
                            <textarea id="userComment" name="comment" required minlength="10" maxlength="500" rows="4" data-translate-placeholder="review_placeholder" placeholder="Share your experience..."></textarea>
                            <small class="char-count"><span id="charCount">0</span>/500</small>
                        </div>

                        <button type="submit" class="submit-btn" data-translate="submit review">Submit Review</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <!-- footer -->
    <footer>
        <div class="container">
            
            <p>&copy; 2026 MU-DORMS. All rights reserved.</p>
        </div>
    </footer>

    <script>
    // نموذج إضافة تعليق في الصفحة الرئيسية
    const homeCommentForm = document.getElementById('commentForm');
    if (homeCommentForm) {
        const textarea = document.getElementById('userComment');
        const charCount = document.getElementById('charCount');

        // عداد الأحرف
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length;
        });

        // إرسال النموذج
        homeCommentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const ratingInput = document.querySelector('input[name="rating"]:checked');
            if (!ratingInput) {
                alert(window.currentLang === 'ar' ? 'الرجاء اختيار التقييم!' : 'Please select a rating!');
                return;
            }

            const formData = {
                name: document.getElementById('userName').value.trim(),
                rating: parseInt(ratingInput.value),
                comment: textarea.value.trim()
            };

            try {
                const response = await fetch('add_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // إعادة تعيين النموذج
                    homeCommentForm.reset();
                    charCount.textContent = '0';

                    // إعادة تحميل التعليقات
                    homeCommentsOffset = 0;
                    loadHomeComments();

                    // إظهار رسالة نجاح
                    alert(window.currentLang === 'ar' ? 'تم إضافة تعليقك بنجاح!' : 'Your review has been added successfully!');
                } else {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    }
    </script>

    <!-- Home Page Comments Script -->
    <script>
// Home Page Comments Script
let homeCommentsOffset = 0;
const homeCommentsLimit = 5;
let isHomeLoading = false;

// تحميل التعليقات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    loadHomeComments();
});

// دالة لتحميل التعليقات
async function loadHomeComments(append = false) {
    if (isHomeLoading) return;
    isHomeLoading = true;

    const loadingEl = document.getElementById('homeCommentsLoading');
    if (!append) {
        loadingEl.style.display = 'block';
    }

    try {
        const response = await fetch(`get_comments.php?offset=${homeCommentsOffset}&limit=${homeCommentsLimit}`);
        const data = await response.json();

        if (data.success) {
            displayHomeComments(data.comments, append);

            // إظهار/إخفاء زر "المزيد"
            const loadMoreContainer = document.getElementById('homeLoadMoreContainer');
            if (data.has_more) {
                loadMoreContainer.style.display = 'block';
            } else {
                loadMoreContainer.style.display = 'none';
            }

            homeCommentsOffset += homeCommentsLimit;
        } else {
            console.error('Error loading comments:', data.error);
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingEl.style.display = 'none';
        isHomeLoading = false;
    }
}

// دالة لعرض التعليقات
function displayHomeComments(comments, append = false) {
    const commentsList = document.getElementById('homeCommentsList');

    if (!append) {
        commentsList.innerHTML = '';
    }

    if (comments.length === 0 && !append) {
        commentsList.innerHTML = '<p class="no-comments" data-translate="no_reviews">No reviews yet. Be the first to review!</p>';
        return;
    }

    comments.forEach(comment => {
        const commentEl = createHomeCommentElement(comment);
        commentsList.appendChild(commentEl);
    });
}

// دالة لإنشاء عنصر تعليق
function createHomeCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'comment-item';

    const stars = '★'.repeat(comment.number_of_stars) + '☆'.repeat(5 - comment.number_of_stars);

    div.innerHTML = `
        <div class="comment-header">
            <div class="comment-author">
                <div class="author-avatar">${comment.name.charAt(0).toUpperCase()}</div>
                <div class="author-info">
                    <h4>${escapeHtml(comment.name)}</h4>
                    <span class="comment-date">${comment.time_ago}</span>
                </div>
            </div>
            <div class="comment-rating">
                <span class="stars">${stars}</span>
            </div>
        </div>
        <div class="comment-body">
            <p>${escapeHtml(comment.comment)}</p>
            ${comment.dorm_name ? `<span class="dorm-badge">${escapeHtml(comment.dorm_name)}</span>` : ''}
        </div>
    `;

    return div;
}

// دالة لتأمين النص من XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// زر تحميل المزيد
document.getElementById('homeLoadMoreBtn').addEventListener('click', () => {
    loadHomeComments(true);
});
</script>

<script src="language-switcher.js"></script>
</body>
</html>