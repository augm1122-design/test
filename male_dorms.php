<?php
session_start();
$gender_filter_value = 'male';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-DORMS - Male Dorm Listings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"><!-- Font Awesome for icons -->
    <link rel="stylesheet" href="rtl-support.css"><!-- RTL Support -->
    <style>
        /* لضبط نمط الخط في الصفحة  */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* لسهولة ادارة الالوان */
        :root {
            --primary-color: #007bff;
            --text-color: #333;
            --light-grey: #f0f0f0;
            --medium-grey: #e0e0e0;
            --dark-grey: #666;
            --border-color: #ccc;
            --card-background: #fff;
            --dropdown-background: #fff;
            --dropdown-border: #ddd;
            --dropdown-text: #333;
        }

        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-grey);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--medium-grey);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: black;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: var(--primary-color);
        }


        .sign-in-btn, .log-out-btn , .profile-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            white-space: nowrap; 
        }

        .sign-in-btn:hover, .log-out-btn:hover, .profile-btn:hover {
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

        .main-image-container {
            position: relative;
            width: 100%;
            height: 300px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .main-image-container .main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.7);
        }

        .image-overlay-content {
            position: absolute;
            color: white;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .image-overlay-content h1 {
            font-size: 2.5rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .image-overlay-content .search-bar-overlay {
            display: flex;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 80%;
            max-width: 500px;
        }

        .image-overlay-content .search-bar-overlay input {
            flex-grow: 1;
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
            background: transparent;
            color: var(--text-color);
        }

        .image-overlay-content .search-bar-overlay button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .image-overlay-content .search-bar-overlay button:hover {
            background-color: #0056b3;
        }

        .filter-sort-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--card-background);
            margin: 1rem auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-width: 1200px;
            flex-wrap: wrap; 
            gap: 1rem; 
        }

        .filter-group {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--dark-grey);
        }

        .filter-dropdown, .sort-dropdown, .search-bar {
            position: relative;
            display: inline-block;
        }

        .dropdown-btn {
            background-color: var(--light-grey);
            color: var(--text-color);
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 150px;
            font-size: 0.95rem;
        }

        .dropdown-btn i {
            margin-left: 0.5rem;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--dropdown-background);
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            border: 1px solid var(--dropdown-border);
            padding: 0.5rem 0;
            max-height: 200px; 
            overflow-y: auto;
        }

        .dropdown-content.active {
            display: block;
        }

        .dropdown-content label {
            display: block;
            padding: 0.6rem 1rem;
            color: var(--dropdown-text);
            cursor: pointer;
            font-weight: 400; 
        }

        .dropdown-content label:hover {
            background-color: var(--light-grey);
        }

        .dropdown-content input[type="radio"],
        .dropdown-content input[type="checkbox"] {
            margin-right: 0.8rem;
        }
        .search-bar {
            display: none; 
        }

        .search-bar input {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 0.95rem;
            width: 200px;
        }

        .search-bar button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: var(--dark-blue);
        }

        .dorm-listings-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dorm-card {
            background-color: var(--card-background);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative; 
        }

        .dorm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .dorm-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--medium-grey);
        }

        .dorm-card-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1; 
        }

        .dorm-card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .dorm-card p {
            font-size: 0.80rem;
            color: var(--dark-grey);
            margin-bottom: 0.5rem;
        }

        .dorm-card .location {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .dorm-card .location i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .dorm-card .amenities-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .dorm-card .amenities-list li {
            background-color: var(--light-grey);
            padding: 0.3rem 0.7rem;
            border-radius: 5px;
            font-size: 0.85rem;
            color: var(--dark-grey);
        }

        .dorm-card .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: auto; 
            text-align: right;
        }

        .favorite-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--dark-grey);
            transition: background-color 0.3s ease, color 0.3s ease;
            z-index: 10;
        }

        .favorite-btn.is-favorite {
            color: red; 
        }

        .favorite-btn:hover {
            background-color: rgba(255, 255, 255, 1);
        }

        .no-dorms-message {
            text-align: center;
            padding: 3rem;
            font-size: 1.2rem;
            color: var(--dark-grey);
        }

        .apply-filters-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: auto; 
        }

        .apply-filters-btn:hover {
            background-color: #0056b3;
        }

        .amenities-clear-btn {
            display: block;
            width: calc(100% - 2rem); 
            padding: 0.6rem 1rem;
            margin: 0.5rem 1rem 0; 
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            font-size: 0.95rem;
            color: var(--text-color);
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .amenities-clear-btn:hover {
            background-color: #e0e0e0;
        }

        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 2rem;
            background-color: white;
            border-top: 1px solid var(--medium-grey);
            color: var(--dark-grey);
        }

        /* تنسيقات الشات بوت */
        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .chatbot-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .chatbot-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.6);
        }

        .chatbot-window {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 500px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        .chatbot-window.active {
            display: flex;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chatbot-header {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-header h3 {
            margin: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .chatbot-close:hover {
            transform: rotate(90deg);
        }

        .chatbot-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .chatbot-message {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chatbot-message.bot {
            justify-content: flex-start;
        }

        .chatbot-message.user {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 70%;
            padding: 0.8rem 1rem;
            border-radius: 15px;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .chatbot-message.bot .message-bubble {
            background-color: white;
            color: var(--text-color);
            border: 1px solid var(--medium-grey);
            border-bottom-left-radius: 5px;
        }

        .chatbot-message.bot .message-bubble a {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .chatbot-message.user .message-bubble {
            background-color: var(--primary-color);
            color: white;
            border-bottom-right-radius: 5px;
        }

        .chatbot-input-area {
            padding: 1rem;
            border-top: 1px solid var(--medium-grey);
            display: flex;
            gap: 0.5rem;
            background-color: white;
        }

        .chatbot-input {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            outline: none;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        .chatbot-input:focus {
            border-color: var(--primary-color);
        }

        .chatbot-send {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .chatbot-send:hover {
            background-color: #0056b3;
        }

        .quick-replies {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .quick-reply-btn {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .quick-reply-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .typing-indicator {
            display: flex;
            gap: 0.3rem;
            padding: 0.8rem 1rem;
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--medium-grey);
            width: fit-content;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--dark-grey);
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .filter-sort-container {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-bar-overlay {
                width: 100% !important;
            }
            .search-bar-overlay input {
                width: calc(100% - 60px);
            }
            .search-bar-overlay button {
                width: 50px;
            }
            .dorm-listings-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .auth-buttons {
                flex-direction: column;
                gap: 5px;
            }
            .sign-in-btn, .log-out-btn {
                width: 100%;
                text-align: center;
            }

            /* تنسيقات الشات بوت للشاشات المتوسطة */
            .chatbot-window {
                width: 90%;
                right: 5%;
                left: 5%;
                height: 450px;
            }
        }
        @media (max-width: 480px) {
            .dorm-listings-container {
                grid-template-columns: 1fr;
            }
            .image-overlay-content h1 {
                font-size: 1.8rem;
            }

            /* تنسيقات الشات بوت للشاشات الصغيرة */
            .chatbot-window {
                width: 100%;
                height: 100%;
                bottom: 0;
                right: 0;
                left: 0;
                border-radius: 0;
            }

            .chatbot-button {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }

            .quick-reply-btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="home_page.php" class="logo">MU-DORMS</a>
        <nav>
            <ul>
                <li>
                    <a href="favorites.php" class="favorite-nav-btn" data-translate="my_favorites" title="My Favorites">
                        <i class="fas fa-heart"></i> <span>My Favorites</span>
                    </a>
                </li>
                <li><a href="female_dorms.php" data-translate="female_dorms">Female Dorms</a></li>
                <li><a href="about.php" data-translate="about">About</a></li>
                <li><a href="contact.php" data-translate="contact">Contact</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <!-- يتحقق من حالة تسجيل الدخول -->
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="user_profile.php" class="profile-btn">
                <i class="fas fa-user"></i>
            </a>
            <a href="logout.php" class="sign-in-btn" data-translate="sign_out">Log Out</a>
        <?php else: ?>
            <a href="login_options.php" class="sign-in-btn" data-translate="sign_in">Sign In</a>
        <?php endif; ?>
        </div>
    </header>

    <!-- زر تبديل اللغة -->
    <button id="languageToggle">العربية</button>

    <div class="main-image-container">
        <!-- للصورة الكبيرة في الجزء العلوي من الصفحه -->
        <img src="https://images.unsplash.com/photo-1562774053-701939374585?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTF8fHVuaXZlcnNpd%D9%90y%20%D8%AC%D8%A7%D9%85%D8%B9%D9%8A%20%D8%B7%D9%84%D8%A8%D8%A9%D9%90%D9%8A%20%D9%83%D8%A7%D9%85%D8%A8%D8%B3%7Cuniversity%20campus%7D&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTF8fHVuaXZlcnNpdHklMjBjYW1wdXN8ZW58MHx8MHx8fDA%3D" alt="Campus" class="main-image">
        <!-- للمحتوى الذي يظهر فوق الصورة -->
        <div class="image-overlay-content">
            <h1 data-translate="Find Your Perfect Male Dorm!">Find Your Perfect Male Dorm!</h1>
            <!-- شريط البحث -->
            <div class="search-bar-overlay">
                <input type="text" id="overlaySearchInput" placeholder="Search for dorms..." data-translate="search_placeholder">
                <button id="overlaySearchBtn" data-translate="search_button"><i class="fas fa-search"></i> Search</button>
            </div>
        </div>
    </div>
   <!-- قسم الفلاتر والترتيب -->
    <!-- كونتينر لكل خيارات الفرز والفلترة -->
    <div class="filter-sort-container">
        <!-- يجمع كل فلتر -->
        <div class="filter-group">
            <!-- كونتينر نطاق السعر -->
            <div class="filter-dropdown">
                <!-- الزر الي بيفتح القائمة -->
                <button class="dropdown-btn" id="priceRangeBtn" data-translate="price_range">Price Range <i class="fas fa-chevron-down"></i></button>
                <!-- القائمة المنسدلة نفسها التي تحتوي على خيارات الفلترة -->
                <div class="dropdown-content" id="priceRangeDropdown">
                    <label><input type="radio" name="price_range" value=""><span data-translate="any_price">Any Price</span></label>
                    <label><input type="radio" name="price_range" value="150_200"><span data-translate="150_200">150 - 200 JOD</span></label>
                    <label><input type="radio" name="price_range" value="200_250"><span data-translate="200_250">200 - 250 JOD</span></label>
                    <label><input type="radio" name="price_range" value="250_300"><span data-translate="250_300">250 - 300 JOD</span></label>
                    <label><input type="radio" name="price_range" value="300_400"><span data-translate="300_400">300 - 400 JOD</span></label>
                    <label><input type="radio" name="price_range" value="400_500"><span data-translate="400_500">400 - 500 JOD</span></label>
                    <label><input type="radio" name="price_range" value="500_600"><span data-translate="500_600">500 - 600 JOD</span></label>
                    <label><input type="radio" name="price_range" value="greater_than_600"><span data-translate="greater_than_600">More than 600 JOD</span></label>
                </div>
            </div>
        </div>

        <div class="filter-group">
            <div class="filter-dropdown">
                <button class="dropdown-btn" id="locationBtn" data-translate="location">Location <i class="fas fa-chevron-down"></i></button>
                <div class="dropdown-content" id="locationDropdown">
                    <label><input type="radio" name="location" value=""><span data-translate="any_location">Any Location</span></label>
                    <label><input type="radio" name="location" value="Mutah"><span data-translate="mutah">Mutah</span></label>
                    <label><input type="radio" name="location" value="Mirwid"><span data-translate="mirwid">Mirwid</span></label>
                    <label><input type="radio" name="location" value="Aleadnania"><span data-translate="aleadnania">Aleadnania</span></label>
                    <label><input type="radio" name="location" value="Zahoum"><span data-translate="zahoum">Zahoum</span></label>
                </div>
            </div>
        </div>

        <div class="filter-group">
            <div class="filter-dropdown">
                <button class="dropdown-btn" id="roomTypeBtn" data-translate="room_type">Room Type <i class="fas fa-chevron-down"></i></button>
                <div class="dropdown-content" id="roomTypeDropdown">
                    <label><input type="radio" name="room_type" value=""><span data-translate="any_room">Any Room Type</span></label>
                    <label><input type="radio" name="room_type" value="Single"><span data-translate="single">Single</span></label>
                    <label><input type="radio" name="room_type" value="Double"><span data-translate="double">Double</span></label>
                    <label><input type="radio" name="room_type" value="Triple"><span data-translate="triple">Triple</span></label>
                </div>
            </div>
        </div>

        <div class="filter-group">
            <div class="filter-dropdown">
                <button class="dropdown-btn" id="amenitiesBtn" data-translate="amenities">Amenities <span id="amenitiesFilterText"></span> <i class="fas fa-chevron-down"></i></button>
                <div class="dropdown-content" id="amenitiesDropdown">
                    <a href="#" class="amenities-clear-btn" id="clearAmenitiesBtn" data-translate="clear_filters">Clear All</a>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search for dorms..." data-translate="search_placeholder">
            <button id="searchBtn" data-translate="search_button"><i class="fas fa-search"></i> Search</button>
        </div>

        <!-- زر تطبيق الفلاتر -->
        <button class="apply-filters-btn" id="applyFiltersBtn">Apply Filters</button>
    </div>

    <!-- قائمة السكنات -->
    <div class="dorm-listings-container" id="dormListings">
    </div>

    <div id="noDormsMessage" class="no-dorms-message" style="display: none;">
        No dorms found matching your criteria.
    </div>

    <footer>
        <p>&copy; 2026 MU-DORMS. <span data-translate="all_rights_reserved">All rights reserved</span>.</p>
    </footer>

    <!-- الشات بوت -->
    <div class="chatbot-container">
        <button class="chatbot-button" id="chatbotToggle">
            <i class="fas fa-comments"></i>
        </button>

        <div class="chatbot-window" id="chatbotWindow">
            <div class="chatbot-header">
                <h3>
                    <i class="fas fa-robot"></i>
                    <span data-translate="chatbot_header">MU-DORMS Assistant</span>
                </h3>
                <button class="chatbot-close" id="chatbotClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="chatbot-messages" id="chatbotMessages">
                <div class="chatbot-message bot">
                    <div class="message-bubble" data-translate="chatbot_welcome">
                        مرحباً! 👋 أنا مساعدك الذكي في MU-DORMS.<br><br>
                        يمكنني مساعدتك في:<br>
                        ✨ <strong>اقتراح السكن المثالي</strong> بناءً على احتياجاتك<br>
                        💬 الإجابة على أسئلتك حول السكنات<br><br>
                        كيف يمكنني مساعدتك اليوم؟
                    </div>
                </div>
                <div class="quick-replies" id="initialQuickReplies">
                    <button class="quick-reply-btn" data-message-en="Suggest a suitable dorm for me" data-message-ar="اقترح لي سكن مناسب" data-translate="chatbot_suggest">🏠 اقترح لي سكن</button>
                    <button class="quick-reply-btn" data-message-en="What are the dorm prices?" data-message-ar="ما هي أسعار السكنات؟" data-translate="chatbot_prices">💰 الأسعار</button>
                    <button class="quick-reply-btn" data-message-en="What amenities are available?" data-message-ar="ما هي المرافق المتوفرة؟" data-translate="chatbot_amenities">✨ المرافق</button>
                    <button class="quick-reply-btn" data-message-en="How do I book a dorm?" data-message-ar="كيف أحجز سكن؟" data-translate="chatbot_booking">📝 الحجز</button>
                </div>
            </div>

            <div class="chatbot-input-area">
                <input type="text" class="chatbot-input" id="chatbotInput" placeholder="اكتب رسالتك هنا..." data-translate="chatbot_placeholder">
                <button class="chatbot-send" id="chatbotSend">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- للتفاعل مع الفلاتر و البحث -->
    <script>
    // ينتظر حتى يتم تحميل محتوى HTML بالكامل قبل تنفيذ أي كود JavaScript
        document.addEventListener('DOMContentLoaded', () => {
            // تعريف المتغيرات التي تشير إلى العناصر في HTML للتعامل معها
            const dormListingsContainer = document.getElementById('dormListings');
            const noDormsMessage = document.getElementById('noDormsMessage');
            const searchInput = document.getElementById('overlaySearchInput'); 
            const overlaySearchBtn = document.getElementById('overlaySearchBtn'); 
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');

            const priceRangeBtn = document.getElementById('priceRangeBtn');
            const priceRangeDropdown = document.getElementById('priceRangeDropdown');
            const locationBtn = document.getElementById('locationBtn');
            const locationDropdown = document.getElementById('locationDropdown');
            const roomTypeBtn = document.getElementById('roomTypeBtn');
            const roomTypeDropdown = document.getElementById('roomTypeDropdown');
            const amenitiesBtn = document.getElementById('amenitiesBtn');
            const amenitiesDropdown = document.getElementById('amenitiesDropdown');
            const amenitiesFilterText = document.getElementById('amenitiesFilterText');
            const clearAmenitiesBtn = document.getElementById('clearAmenitiesBtn');

            // مصفوفة لتسهيل التعامل مع القوائم المنسدلة
            const filterDropdowns = [
                { button: priceRangeBtn, content: priceRangeDropdown },
                { button: locationBtn, content: locationDropdown },
                { button: roomTypeBtn, content: roomTypeDropdown },
                { button: amenitiesBtn, content: amenitiesDropdown }
            ];

            // إنشاء مجموعة لتخزين  السكنات التي أضافها المستخدم إلى المفضلة
            // عن طريق ال id الخاص بكل سكن
            let favoriteDormIds = new Set();

            // جلب قائمة المرافق من قاعدة البيانات وعرضها في قائمة الفلاتر
            const fetchAmenities = async () => {
                try {
                    const lang = window.currentLang || 'en';
                    const response = await fetch(`get_amenities.php?lang=${lang}`);
                    const data = await response.json();
                    if (data.success) {
                        amenitiesDropdown.innerHTML = ''; 
                        data.amenities.forEach(amenity => {
                            const label = document.createElement('label');
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'amenities';
                            checkbox.value = amenity.amenity_id;
                            label.appendChild(checkbox);
                            label.appendChild(document.createTextNode(amenity.amenity_name));
                            amenitiesDropdown.appendChild(label);
                        });
                        // إضافة زر مسح الكل في أسفل قائمة المرافق
                        const clearText = window.t ? window.t('clear_all') : 'Clear All';
                        const clearButtonHTML = `<a href="#" class="amenities-clear-btn" id="clearAmenitiesBtn" data-translate="clear_all">${clearText}</a>`;
                        amenitiesDropdown.insertAdjacentHTML('beforeend', clearButtonHTML);

                        // إضافة مستمع حدث لزر مسح الكل
                        document.getElementById('clearAmenitiesBtn').addEventListener('click', (event) => {
                            event.preventDefault(); 
                            // إلغاء تحديد جميع المربعات
                            document.querySelectorAll('#amenitiesDropdown input[type="checkbox"]').forEach(checkbox => {
                                checkbox.checked = false;
                            });
                            updateAmenitiesFilterText();
                            fetchDorms(); 
                        });

                    } else {
                        console.error('Error fetching amenities:', data.message);
                    }
                } catch (error) {
                    console.error('Network error fetching amenities:', error);
                }
            };


            // تحديث نص زر المرافق ليعكس عدد المرافق المحددة
            const updateAmenitiesFilterText = () => {
                // يحسب عدد المرافق المحددة ويحدث النص في الزر
                const checkedAmenities = document.querySelectorAll('#amenitiesDropdown input[type="checkbox"]:checked');
                // إذا كان هناك مرافق محددة
                if (checkedAmenities.length > 0) {
                    // تحديث نص الزر ليعكس عدد المرافق المحددة
                    amenitiesFilterText.textContent = `(${checkedAmenities.length} selected)`;
                } else {
                    // إذا لم تكن هناك مرافق محددة، إعادة النص إلى الوضع الافتراضي
                    amenitiesFilterText.textContent = '';
                }
            };

            // جلب السكنات المفضلة للمستخدم المسجل الدخول
            const fetchFavorites = async () => {
                try {
                    const response = await fetch('fetch_favorites.php');
                    const data = await response.json();
                    if (data.success) {
                        favoriteDormIds.clear();
                        data.dorms.forEach(dorm => favoriteDormIds.add(dorm.dorm_id));
                        fetchDorms(); 
                    } else {
                        if (data.status === 'not_logged_in') {
                            console.log('User not logged in, fetching dorms without favorite status.');
                            fetchDorms();
                        } else {
                            console.error('Error fetching favorites:', data.message);
                            fetchDorms(); 
                        }
                    }
                } catch (error) {
                    console.error('Network error fetching favorites:', error);
                    fetchDorms(); 
                }
            };

            // إضافة أو إزالة سكن من المفضلة
            const toggleFavorite = async (dormId, isFavorite) => {
                const action = isFavorite ? 'remove' : 'add';
                const formData = new FormData();
                formData.append('dorm_id', dormId);
                formData.append('action', action);

                try {
                    const response = await fetch('toggle_favorite.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.status === 'not_logged_in') {
                        alert(data.message);
                        window.location.href = 'log_in_student.php';
                        return;
                    }

                    if (data.status === 'success') {
                        console.log(data.message);
                        if (action === 'add') {
                            favoriteDormIds.add(dormId);
                        } else {
                            favoriteDormIds.delete(dormId);
                        }
                        const heartIcon = document.querySelector(`.favorite-btn[data-dorm-id="${dormId}"] i`);
                        if (heartIcon) {
                            if (action === 'add') {
                                heartIcon.classList.add('is-favorite');
                            } else {
                                heartIcon.classList.remove('is-favorite');
                            }
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error toggling favorite:', error);
                    alert('An error occurred while updating favorites. Please try again later.');
                }
            };

            // جلب السكنات من الخادم بناءً على معايير البحث والفلترة
            // وعرضها في الصفحة
            const fetchDorms = async () => {
                dormListingsContainer.innerHTML = ''; 
                noDormsMessage.style.display = 'none'; 

                const searchQuery = searchInput.value.trim();
                const priceRange = document.querySelector('input[name="price_range"]:checked')?.value || '';
                const location = document.querySelector('input[name="location"]:checked')?.value || '';
                const roomType = document.querySelector('input[name="room_type"]:checked')?.value || '';
                const selectedAmenities = Array.from(document.querySelectorAll('#amenitiesDropdown input[type="checkbox"]:checked'))
                                                .map(cb => cb.value);

                const params = new URLSearchParams();
                params.append('gender', '<?php echo $gender_filter_value; ?>');
                params.append('lang', window.currentLang || 'en'); // إضافة اللغة الحالية

                if (searchQuery) params.append('search', searchQuery);
                if (priceRange) params.append('price_range', priceRange);
                if (location) params.append('location', location);
                if (roomType) params.append('room_type', roomType);
                selectedAmenities.forEach(amenityId => params.append('amenities[]', amenityId));

                try {
                    const response = await fetch(`fetch_dorms.php?${params.toString()}`);
                    const data = await response.json();

                    if (data.success && data.dorms.length > 0) {
                        data.dorms.forEach(dorm => {
                            const isFavorite = favoriteDormIds.has(dorm.dorm_id);
                            const card = `
                                <div class="dorm-card">
                                    <button class="favorite-btn ${isFavorite ? 'is-favorite' : ''}" data-dorm-id="${dorm.dorm_id}">
                                        <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                                    </button>
<a href="dorm_details.php?id=${dorm.dorm_id}">
    <img src="${dorm.image_url || 'https://via.placeholder.com/300x200?text=No+Image'}" alt="${dorm.name}">
</a>
                                    <div class="dorm-card-content">
                                        <h3>${dorm.name}</h3>
                                        <p>${dorm.description}</p>
                                    </div>
                                </div>
                            `;
                            dormListingsContainer.insertAdjacentHTML('beforeend', card);
                        });

                        document.querySelectorAll('.favorite-btn').forEach(button => {
                            button.addEventListener('click', (event) => {
                                const dormId = parseInt(button.dataset.dormId);
                                const wasFavorite = button.classList.contains('is-favorite'); 

                                button.classList.toggle('is-favorite');

                                toggleFavorite(dormId, wasFavorite);

                                
                            });
                        });

                    } else {
                        noDormsMessage.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching dorms:', error);
                    noDormsMessage.textContent = 'An error occurred while fetching dorm listings. Please try again later.';
                    noDormsMessage.style.display = 'block';
                }
            };

            filterDropdowns.forEach(({ button, content }) => {
                button.addEventListener('click', (event) => {
                    filterDropdowns.forEach(other => {
                        if (other.content !== content && other.content.classList.contains('active')) {
                            other.content.classList.remove('active');
                        }
                    });
                    content.classList.toggle('active');
                    event.stopPropagation(); 
                });

                content.addEventListener('click', (event) => {
                    
                    if (event.target.tagName === 'INPUT' && event.target.type === 'radio') {
                        content.classList.remove('active');
                        fetchDorms(); 
                    } else if (event.target.tagName === 'INPUT' && event.target.type === 'checkbox') {
                        updateAmenitiesFilterText();
                    } else {
                        event.stopPropagation();
                    }
                });
            });

            document.addEventListener('click', (event) => {
                filterDropdowns.forEach(dropdown => {
                    if (dropdown.content.classList.contains('active') && !dropdown.content.contains(event.target) && event.target !== dropdown.button) {
                        dropdown.content.classList.remove('active');
                    }
                });
            });

            applyFiltersBtn.addEventListener('click', fetchDorms);

            overlaySearchInput.addEventListener('keypress', (event) => {
                if (event.key === 'Enter') {
                    fetchDorms();
                }
            });

            overlaySearchBtn.addEventListener('click', fetchDorms);

            // ============ وظائف الشات بوت التفاعلي ============
            const chatbotToggle = document.getElementById('chatbotToggle');
            const chatbotWindow = document.getElementById('chatbotWindow');
            const chatbotClose = document.getElementById('chatbotClose');
            const chatbotMessages = document.getElementById('chatbotMessages');
            const chatbotInput = document.getElementById('chatbotInput');
            const chatbotSend = document.getElementById('chatbotSend');

            // حالة المحادثة التفاعلية
            let conversationState = {
                step: 0,
                budget: null,
                location: null,
                roomType: null,
                amenities: [],
                isInteractive: false,
                language: 'ar'
            };

            // فتح وإغلاق نافذة الشات
            chatbotToggle.addEventListener('click', () => {
                chatbotWindow.classList.toggle('active');
                if (chatbotWindow.classList.contains('active')) {
                    chatbotInput.focus();
                }
            });

            chatbotClose.addEventListener('click', () => {
                chatbotWindow.classList.remove('active');
            });

            // إضافة رسالة إلى الشات
            const addMessage = (message, isUser = false, includeButtons = false, buttons = []) => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chatbot-message ${isUser ? 'user' : 'bot'}`;

                let content = '<div class="message-bubble">' + message.replace(/\n/g, '<br>') + '</div>';

                if (includeButtons && buttons.length > 0) {
                    content += '<div class="quick-replies">';
                    buttons.forEach(btn => {
                        content += '<button class="quick-reply-btn interactive-btn" data-value="' + btn.value + '">' + btn.label + '</button>';
                    });
                    content += '</div>';
                }

                messageDiv.innerHTML = content;
                chatbotMessages.appendChild(messageDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            };

            // إظهار مؤشر الكتابة
            const showTypingIndicator = () => {
                const typingDiv = document.createElement('div');
                typingDiv.className = 'chatbot-message bot typing-indicator-container';
                typingDiv.innerHTML = `
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                `;
                chatbotMessages.appendChild(typingDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                return typingDiv;
            };

            // إزالة مؤشر الكتابة
            const removeTypingIndicator = (indicator) => {
                if (indicator && indicator.parentNode) {
                    indicator.parentNode.removeChild(indicator);
                }
            };

            // بدء المحادثة التفاعلية لاقتراح السكن
            const startInteractiveConversation = (userLang = 'ar') => {
                conversationState = {
                    step: 1,
                    budget: null,
                    location: null,
                    roomType: null,
                    amenities: [],
                    isInteractive: true,
                    language: userLang
                };

                const typingIndicator = showTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const welcomeMsg = userLang === 'ar'
                        ? 'رائع! سأساعدك في إيجاد السكن المثالي 🏠'
                        : 'Great! I will help you find the perfect dorm 🏠';
                    addMessage(welcomeMsg, false);

                    setTimeout(() => {
                        const typingIndicator2 = showTypingIndicator();
                        setTimeout(() => {
                            removeTypingIndicator(typingIndicator2);

                            if (userLang === 'ar') {
                                addMessage('ما هي ميزانيتك الشهرية؟ 💰', false, true, [
                                    { label: '150-200 دينار', value: '150_200' },
                                    { label: '200-250 دينار', value: '200_250' },
                                    { label: '250-300 دينار', value: '250_300' },
                                    { label: '300-400 دينار', value: '300_400' },
                                    { label: '400-500 دينار', value: '400_500' },
                                    { label: '500-600 دينار', value: '500_600' },
                                    { label: 'أكثر من 600', value: 'greater_than_600' }
                                ]);
                            } else {
                                addMessage('What is your monthly budget? 💰', false, true, [
                                    { label: '150-200 JOD', value: '150_200' },
                                    { label: '200-250 JOD', value: '200_250' },
                                    { label: '250-300 JOD', value: '250_300' },
                                    { label: '300-400 JOD', value: '300_400' },
                                    { label: '400-500 JOD', value: '400_500' },
                                    { label: '500-600 JOD', value: '500_600' },
                                    { label: 'More than 600', value: 'greater_than_600' }
                                ]);
                            }
                        }, 800);
                    }, 500);
                }, 1000);
            };

            // معالجة الخطوات التفاعلية
            const handleInteractiveStep = (value) => {
                const lang = conversationState.language || 'ar';

                if (conversationState.step === 1) {
                    conversationState.budget = value;
                    conversationState.step = 2;

                    const typingIndicator = showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);

                        if (lang === 'ar') {
                            addMessage('ممتاز! أين تفضل أن يكون موقع السكن؟ 📍', false, true, [
                                { label: 'مؤتة', value: 'Mutah' },
                                { label: 'مرود', value: 'Mirwid' },
                                { label: 'الثانية', value: 'Aleadnania' },
                                { label: 'زحوم', value: 'Zahoum' },
                                { label: 'أي موقع', value: '' }
                            ]);
                        } else {
                            addMessage('Excellent! Where do you prefer the dorm location? 📍', false, true, [
                                { label: 'Mutah', value: 'Mutah' },
                                { label: 'Mirwid', value: 'Mirwid' },
                                { label: 'Aleadnania', value: 'Aleadnania' },
                                { label: 'Zahoum', value: 'Zahoum' },
                                { label: 'Any location', value: '' }
                            ]);
                        }
                    }, 1000);

                } else if (conversationState.step === 2) {
                    conversationState.location = value;
                    conversationState.step = 3;

                    const typingIndicator = showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);

                        if (lang === 'ar') {
                            addMessage('رائع! ما نوع الغرفة التي تفضلها؟ 🛏️', false, true, [
                                { label: 'فردية', value: 'Single' },
                                { label: 'مزدوجة', value: 'Double' },
                                { label: 'ثلاثية', value: 'Triple' },
                                { label: 'لا يهم', value: '' }
                            ]);
                        } else {
                            addMessage('Great! What room type do you prefer? 🛏️', false, true, [
                                { label: 'Single', value: 'Single' },
                                { label: 'Double', value: 'Double' },
                                { label: 'Triple', value: 'Triple' },
                                { label: 'No preference', value: '' }
                            ]);
                        }
                    }, 1000);

                } else if (conversationState.step === 3) {
                    conversationState.roomType = value;
                    conversationState.step = 4;

                    const typingIndicator = showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);

                        if (lang === 'ar') {
                            addMessage('رائع! ما هي المرافق المهمة بالنسبة لك؟ ✨\n(يمكنك اختيار أكثر من واحدة)', false, true, [
                                { label: '📶 واي فاي', value: '6' },
                                { label: '🍳 مطبخ', value: '9' },
                                { label: '🅿️ مواقف سيارات', value: '5' },
                                { label: '🏋️ صالة رياضية', value: '3' },
                                { label: '🏊 مسبح', value: '4' },
                                { label: '🔒 حراسة 24/7', value: '2' },
                                { label: 'لا يهم', value: 'skip' }
                            ]);
                        } else {
                            addMessage('Great! What amenities are important to you? ✨\n(You can select multiple)', false, true, [
                                { label: '📶 WiFi', value: '6' },
                                { label: '🍳 Kitchen', value: '9' },
                                { label: '🅿️ Parking', value: '5' },
                                { label: '🏋️ Gym', value: '3' },
                                { label: '🏊 Pool', value: '4' },
                                { label: '🔒 24/7 Security', value: '2' },
                                { label: 'No preference', value: 'skip' }
                            ]);
                        }
                    }, 1000);

                } else if (conversationState.step === 4) {
                    if (value === 'skip') {
                        conversationState.amenities = [];
                        conversationState.step = 5;
                        searchDorms();
                    } else if (value === 'search_now') {
                        conversationState.step = 5;
                        searchDorms();
                    } else {
                        const amenityNamesAr = {
                            '6': '📶 واي فاي',
                            '9': '🍳 مطبخ',
                            '5': '🅿️ مواقف سيارات',
                            '3': '🏋️ صالة رياضية',
                            '4': '🏊 مسبح',
                            '2': '🔒 حراسة 24/7'
                        };

                        const amenityNamesEn = {
                            '6': '📶 WiFi',
                            '9': '🍳 Kitchen',
                            '5': '🅿️ Parking',
                            '3': '🏋️ Gym',
                            '4': '🏊 Pool',
                            '2': '🔒 24/7 Security'
                        };

                        if (!conversationState.amenities.includes(value)) {
                            conversationState.amenities.push(value);
                        }

                        const typingIndicator = showTypingIndicator();
                        setTimeout(() => {
                            removeTypingIndicator(typingIndicator);

                            if (lang === 'ar') {
                                const selectedAmenities = conversationState.amenities.map(a => amenityNamesAr[a] || a).join(', ');
                                addMessage(`تم إضافة ${amenityNamesAr[value]} ✅\n\nالمرافق المختارة: ${selectedAmenities}\n\nهل تريد إضافة مرافق أخرى؟`, false, true, [
                                    { label: '📶 واي فاي', value: '6' },
                                    { label: '🍳 مطبخ', value: '9' },
                                    { label: '🅿️ مواقف سيارات', value: '5' },
                                    { label: '🏋️ صالة رياضية', value: '3' },
                                    { label: '🏊 مسبح', value: '4' },
                                    { label: '🔒 حراسة 24/7', value: '2' },
                                    { label: '✅ ابحث الآن', value: 'search_now' }
                                ]);
                            } else {
                                const selectedAmenities = conversationState.amenities.map(a => amenityNamesEn[a] || a).join(', ');
                                addMessage(`Added ${amenityNamesEn[value]} ✅\n\nSelected amenities: ${selectedAmenities}\n\nWould you like to add more amenities?`, false, true, [
                                    { label: '📶 WiFi', value: '6' },
                                    { label: '🍳 Kitchen', value: '9' },
                                    { label: '🅿️ Parking', value: '5' },
                                    { label: '🏋️ Gym', value: '3' },
                                    { label: '🏊 Pool', value: '4' },
                                    { label: '🔒 24/7 Security', value: '2' },
                                    { label: '✅ Search Now', value: 'search_now' }
                                ]);
                            }
                        }, 500);
                    }
                }
            };

            // دالة البحث عن السكنات
            const searchDorms = () => {
                const lang = conversationState.language || 'ar';
                const typingIndicator = showTypingIndicator();
                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);

                    const searchMsg = lang === 'ar'
                        ? 'ممتاز! الآن دعني أبحث عن أفضل السكنات المناسبة لك... 🔍✨'
                        : 'Excellent! Now let me search for the best dorms for you... 🔍✨';
                    addMessage(searchMsg, false);

                    setTimeout(() => {
                        findMatchingDorms();
                    }, 1500);
                }, 1000);
            };

            // البحث عن السكنات المناسبة
            const findMatchingDorms = async () => {
                const params = new URLSearchParams();
                params.append('gender', '<?php echo $gender_filter_value; ?>');

                if (conversationState.budget) params.append('price_range', conversationState.budget);
                if (conversationState.location) params.append('location', conversationState.location);
                if (conversationState.roomType) params.append('room_type', conversationState.roomType);

                if (conversationState.amenities.length > 0) {
                    conversationState.amenities.forEach(amenityId => {
                        params.append('amenities[]', amenityId);
                    });
                }

                try {
                    const response = await fetch(`fetch_dorms.php?${params.toString()}`);
                    const data = await response.json();

                    const lang = conversationState.language || 'ar';
                    const typingIndicator = showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);

                        if (data.success && data.dorms && data.dorms.length > 0) {
                            const topDorms = data.dorms.slice(0, 3);

                            const priceMapAr = {
                                '150_200': '150-200 دينار/شهر',
                                '200_250': '200-250 دينار/شهر',
                                '250_300': '250-300 دينار/شهر',
                                '300_400': '300-400 دينار/شهر',
                                '400_500': '400-500 دينار/شهر',
                                '500_600': '500-600 دينار/شهر',
                                'greater_than_600': 'أكثر من 600 دينار/شهر'
                            };

                            const priceMapEn = {
                                '150_200': '150-200 JOD/month',
                                '200_250': '200-250 JOD/month',
                                '250_300': '250-300 JOD/month',
                                '300_400': '300-400 JOD/month',
                                '400_500': '400-500 JOD/month',
                                '500_600': '500-600 JOD/month',
                                'greater_than_600': 'More than 600 JOD/month'
                            };

                            let message = '';
                            if (lang === 'ar') {
                                message = 'وجدت ' + data.dorms.length + ' سكن مناسب لك! 🎉\n\nإليك أفضل الخيارات:\n\n';
                                topDorms.forEach((dorm, index) => {
                                    message += (index + 1) + '. <strong>' + dorm.name + '</strong>\n';
                                    message += '   📍 ' + (dorm.location || 'غير محدد') + '\n';
                                    const priceText = priceMapAr[dorm.price_range] || 'السعر غير متوفر';
                                    message += '   💰 ' + priceText + '\n';
                                    message += '   <a href="dorm_details.php?id=' + dorm.dorm_id + '" target="_blank" style="color: var(--primary-color); font-weight: 600;">👉 عرض التفاصيل والحجز</a>\n\n';
                                });
                                if (data.dorms.length > 3) {
                                    message += 'يمكنك رؤية جميع الـ ' + data.dorms.length + ' سكن في الصفحة الرئيسية! 😊';
                                }
                            } else {
                                message = 'Found ' + data.dorms.length + ' suitable dorms for you! 🎉\n\nHere are the best options:\n\n';
                                topDorms.forEach((dorm, index) => {
                                    message += (index + 1) + '. <strong>' + dorm.name + '</strong>\n';
                                    message += '   📍 ' + (dorm.location || 'Not specified') + '\n';
                                    const priceText = priceMapEn[dorm.price_range] || 'Price not available';
                                    message += '   💰 ' + priceText + '\n';
                                    message += '   <a href="dorm_details.php?id=' + dorm.dorm_id + '" target="_blank" style="color: var(--primary-color); font-weight: 600;">👉 View Details & Book</a>\n\n';
                                });
                                if (data.dorms.length > 3) {
                                    message += 'You can see all ' + data.dorms.length + ' dorms on the main page! 😊';
                                }
                            }

                            addMessage(message, false);
                            applyFiltersToPage();
                        } else {
                            const noResultsMsg = lang === 'ar'
                                ? 'عذراً، لم أجد سكنات تطابق معاييرك. يمكنك تعديل خياراتك أو تصفح جميع السكنات المتاحة'
                                : 'Sorry, I couldn\'t find dorms matching your criteria. You can adjust your options or browse all available dorms';
                            addMessage(noResultsMsg, false);
                        }

                        setTimeout(() => {
                            conversationState.isInteractive = false;
                            conversationState.step = 0;

                            if (lang === 'ar') {
                                addMessage('هل تريد مساعدة في شيء آخر؟ 😊', false, true, [
                                    { label: 'بحث جديد', value: 'new_search' },
                                    { label: 'سؤال آخر', value: 'other' }
                                ]);
                            } else {
                                addMessage('Would you like help with something else? 😊', false, true, [
                                    { label: 'New search', value: 'new_search' },
                                    { label: 'Other question', value: 'other' }
                                ]);
                            }
                        }, 1000);

                    }, 1500);

                } catch (error) {
                    console.error('Error finding dorms:', error);
                    const lang = conversationState.language || 'ar';
                    const errorMsg = lang === 'ar'
                        ? 'عذراً، حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى. 😔'
                        : 'Sorry, an error occurred during the search. Please try again. 😔';
                    addMessage(errorMsg, false);
                    conversationState.isInteractive = false;
                    conversationState.step = 0;
                }
            };

            // تطبيق الفلاتر على الصفحة
            const applyFiltersToPage = () => {
                if (conversationState.budget) {
                    const priceRadio = document.querySelector(`input[name="price_range"][value="${conversationState.budget}"]`);
                    if (priceRadio) priceRadio.checked = true;
                }

                if (conversationState.location) {
                    const locationRadio = document.querySelector(`input[name="location"][value="${conversationState.location}"]`);
                    if (locationRadio) locationRadio.checked = true;
                }

                if (conversationState.roomType) {
                    const roomTypeRadio = document.querySelector(`input[name="room_type"][value="${conversationState.roomType}"]`);
                    if (roomTypeRadio) roomTypeRadio.checked = true;
                }

                fetchDorms();
            };

            // كشف اللغة من النص
            const detectLanguage = (text) => {
                const arabicPattern = /[\u0600-\u06FF]/;
                return arabicPattern.test(text) ? 'ar' : 'en';
            };

            // الحصول على رد الشات بوت
            const getBotResponse = (userMessage) => {
                const message = userMessage.toLowerCase();
                const lang = detectLanguage(userMessage);

                if (message.includes('اقترح') || message.includes('ساعدني') || message.includes('ابحث لي') ||
                    message.includes('suggest') || message.includes('recommend') || message.includes('find me')) {
                    startInteractiveConversation(lang);
                    return null;
                }

                if (message.includes('سعر') || message.includes('أسعار') || message.includes('تكلفة') || message.includes('price')) {
                    return lang === 'ar'
                        ? 'أسعار السكنات تتراوح من 150 إلى 800 دينار شهرياً حسب نوع الغرفة والمرافق المتوفرة. يمكنك استخدام فلتر "Price Range" أعلى الصفحة لتحديد نطاق السعر المناسب لك. 💰'
                        : 'Dorm prices range from 150 to 800 JOD per month depending on room type and amenities. You can use the "Price Range" filter at the top to select your budget. 💰';
                }

                if (message.includes('مراف') || message.includes('خدمات') || message.includes('amenities')) {
                    return lang === 'ar'
                        ? 'السكنات توفر مرافق متنوعة مثل: واي فاي، مطبخ، مواقف سيارات، صالة رياضية، مسبح، وغيرها. يمكنك استخدام فلتر "Amenities" لاختيار المرافق التي تحتاجها. ✨'
                        : 'Dorms offer various amenities such as: WiFi, Kitchen, Parking, Gym, Pool, and more. You can use the "Amenities" filter to select what you need. ✨';
                }

                if (message.includes('حجز') || message.includes('booking') || message.includes('احجز')) {
                    return lang === 'ar'
                        ? 'للحجز، اختر السكن المناسب من القائمة، ثم اضغط على تفاصيل السكن وستجد زر الحجز هناك. تأكد من تسجيل الدخول أولاً! 📝'
                        : 'To book, select a suitable dorm from the list, click on dorm details and you will find the booking button there. Make sure to sign in first! 📝';
                }

                if (message.includes('موقع') || message.includes('مواقع') || message.includes('location') || message.includes('أين')) {
                    return lang === 'ar'
                        ? 'السكنات متوفرة في عدة مواقع قريبة من الجامعة: مؤتة، مرود، الثانية، وزحوم. استخدم فلتر "Location" لاختيار الموقع المناسب. 📍'
                        : 'Dorms are available in several locations near the university: Mutah, Mirwid, Aleadnania, and Zahoum. Use the "Location" filter to choose your preferred area. 📍';
                }

                if (message.includes('غرفة') || message.includes('room') || message.includes('نوع')) {
                    return lang === 'ar'
                        ? 'نوفر ثلاثة أنواع من الغرف: فردية (Single)، مزدوجة (Double)، وثلاثية (Triple). يمكنك الفلترة حسب نوع الغرفة من القائمة أعلاه. 🛏️'
                        : 'We offer three room types: Single, Double, and Triple. You can filter by room type from the menu above. 🛏️';
                }

                if (message.includes('مفضل') || message.includes('favorite') || message.includes('قلب')) {
                    return lang === 'ar'
                        ? 'يمكنك إضافة أي سكن للمفضلة بالضغط على أيقونة القلب ❤️ على بطاقة السكن. ستجد جميع سكناتك المفضلة في صفحة "My Favorites". 💙'
                        : 'You can add any dorm to favorites by clicking the heart icon ❤️ on the dorm card. Find all your favorites in the "My Favorites" page. 💙';
                }

                if (message.includes('بحث') || message.includes('search') || message.includes('ابحث')) {
                    return lang === 'ar'
                        ? 'يمكنك البحث عن سكن معين باستخدام شريط البحث في أعلى الصفحة. اكتب اسم السكن أو أي كلمة مفتاحية واضغط على زر البحث. 🔍'
                        : 'You can search for a specific dorm using the search bar at the top. Type the dorm name or any keyword and click search. 🔍';
                }

                if (message.includes('تسجيل') || message.includes('دخول') || message.includes('login') || message.includes('حساب')) {
                    return lang === 'ar'
                        ? 'لتسجيل الدخول، اضغط على زر "Sign In" في أعلى الصفحة. إذا لم يكن لديك حساب، يمكنك إنشاء حساب جديد من صفحة التسجيل. 👤'
                        : 'To sign in, click the "Sign In" button at the top. If you don\'t have an account, you can create one from the registration page. 👤';
                }

                if (message.includes('إناث') || message.includes('female') || message.includes('بنات')) {
                    return lang === 'ar'
                        ? 'يمكنك الانتقال إلى صفحة سكنات الإناث من القائمة العلوية بالضغط على "Female Dorms". 👩'
                        : 'You can go to the female dorms page from the top menu by clicking "Female Dorms". 👩';
                }

                if (message.includes('مساعدة') || message.includes('help')) {
                    return lang === 'ar'
                        ? 'يسعدني مساعدتك! يمكنك سؤالي عن: الأسعار، المرافق، الحجز، المواقع، أنواع الغرف، أو يمكنني <strong>اقتراح سكن مناسب لك</strong>! فقط قل "اقترح لي سكن" 😊'
                        : 'Happy to help! You can ask me about: prices, amenities, booking, locations, room types, or I can <strong>suggest a suitable dorm for you</strong>! Just say "suggest a dorm" 😊';
                }

                if (message.includes('شكر') || message.includes('thank') || message.includes('مشكور')) {
                    return lang === 'ar'
                        ? 'العفو! سعيد بمساعدتك. إذا كان لديك أي استفسار آخر، لا تتردد في السؤال! 😊'
                        : 'You\'re welcome! Happy to help. If you have any other questions, don\'t hesitate to ask! 😊';
                }

                if (message.includes('مرحبا') || message.includes('hello') || message.includes('hi') || message.includes('السلام')) {
                    return lang === 'ar'
                        ? 'مرحباً بك! 👋 كيف يمكنني مساعدتك في العثور على السكن المناسب؟ يمكنني <strong>اقتراح سكن مناسب لك</strong> بناءً على احتياجاتك! 🏠'
                        : 'Welcome! 👋 How can I help you find the perfect dorm? I can <strong>suggest a suitable dorm</strong> based on your needs! 🏠';
                }

                return lang === 'ar'
                    ? 'عذراً، لم أفهم سؤالك بشكل كامل. يمكنك سؤالي عن: الأسعار، المرافق، الحجز، المواقع، أنواع الغرف، أو قل "<strong>اقترح لي سكن</strong>" وسأساعدك في إيجاد السكن المثالي! 🤔'
                    : 'Sorry, I didn\'t fully understand your question. You can ask me about: prices, amenities, booking, locations, room types, or say "<strong>suggest a dorm</strong>" and I\'ll help you find the perfect one! 🤔';
            };

            // إرسال رسالة
            const sendMessage = () => {
                const message = chatbotInput.value.trim();
                if (message === '') return;

                addMessage(message, true);
                chatbotInput.value = '';

                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const response = getBotResponse(message);
                    if (response) {
                        addMessage(response, false);
                    }
                }, 1000 + Math.random() * 1000);
            };

            chatbotSend.addEventListener('click', sendMessage);

            chatbotInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('quick-reply-btn')) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (e.target.classList.contains('interactive-btn')) {
                        const value = e.target.dataset.value;
                        const label = e.target.textContent;

                        addMessage(label, true);

                        if (value === 'new_search') {
                            const lang = conversationState.language || 'ar';
                            startInteractiveConversation(lang);
                        } else if (value === 'other') {
                            conversationState.isInteractive = false;
                            conversationState.step = 0;
                            const lang = conversationState.language || 'ar';
                            const msg = lang === 'ar' ? 'تفضل، اسألني أي سؤال! 😊' : 'Go ahead, ask me anything! 😊';
                            addMessage(msg, false);
                        } else {
                            handleInteractiveStep(value);
                        }
                    } else {
                        // استخدام اللغة الحالية من window.currentLang (من language-switcher.js)
                        const currentLang = window.currentLang || 'en';
                        const message = currentLang === 'ar'
                            ? e.target.dataset.messageAr
                            : e.target.dataset.messageEn;

                        if (message) {
                            chatbotInput.value = message;
                            sendMessage();
                        }
                    }
                }
            });

            // يتم استدعاؤهما في النهاية لبدء عملية جلب البيانات عند تحميل الصفحة
            fetchAmenities();
            fetchFavorites();
        });
    </script>

    <!-- Language Switcher Script -->
    <script src="language-switcher.js"></script>
</body>
</html>