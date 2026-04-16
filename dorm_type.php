<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Dorm Type | MU-DORMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
            align-items: center;
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

        #languageToggle {
            background-color: #007bff; 
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        #languageToggle:hover {
            background-color: #0056b3; 
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
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
            color: #111112ff;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .main-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            filter: brightness(70%);
        }

        .section-title {
            text-align: center;
            margin: 40px 0 30px;
            font-size: 2.2rem;
            color: #343a40;
        }

        .dorm-options {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 20px;
            flex-wrap: wrap;
        }

        .dorm-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            text-decoration: none;
            color: #212529;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .dorm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .dorm-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .dorm-card h3 {
            padding: 15px;
            font-size: 1.5rem;
            color: #007bff;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <a href="home_page.php" class="logo">MU-DORMS</a>
    <nav>
        <ul class="nav-links">
            <li>
                <a href="favorites.php" class="favorite-nav-btn" data-translate="my_favorites" title="المفضلة">
                    <i class="fas fa-heart"></i> <span>My Favorites</span>
                </a>
            </li>
            <li><a href="about.php" data-translate="about">About</a></li>
            <li><a href="contact.php" data-translate="Contact">Contact</a></li>
            <li><button id="languageToggle">العربية</button></li>
        </ul>
    </nav>
    <div class="auth-buttons">
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="user_profile.php" class="profile-btn">
                <i class="fas fa-user"></i> 
            </a>
            <a href="logout.php" class="sign-in-btn" data-translate="Log out">Log Out</a>
        <?php else: ?>
            <a href="log_in_student.php" class="sign-in-btn" data-translate="sign in">Sign In</a>
        <?php endif; ?>
    </div>
</header>

<img src="https://images.unsplash.com/photo-1562774053-701939374585?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h31ufXwwfHwwfHww" alt="Campus" class="main-image">

<h2 class="section-title" data-translate="select_dorm_title">Select Dorm Type</h2>

<section class="dorm-options">
    <a href="female_dorms.php" class="dorm-card">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStMSTm3A1pVrwmwl2F2LkKhHavnvy10XJnfw&s" alt="Female Dorm">
        <h3 data-translate="female_dorms">Female Dorms</h3>
    </a>
    <a href="male_dorms.php" class="dorm-card">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVyKD7OAKwEIVYJhsSUQM2fxywlnokRZ_gXA&s" alt="Male Dorm">
        <h3 data-translate="male_dorms">Male Dorms</h3>
    </a>
</section>

<footer>
    <p>&copy; 2026 MU-DORMS. All rights reserved.</p>
</footer>

<script src="language-switcher.js"></script>
</body>
</html>