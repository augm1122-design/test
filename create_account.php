<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | MU-DORMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #212529;
        }

        header {
            background-color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #000;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #007bff;
        }

        .login-btn {
            padding: 8px 20px;
            background-color: #007bff;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            color: #f8f6f6ff;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }

        .main-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
            max-width: 1200px;
            margin: auto;
            gap: 40px;
        }

        .form-container {
            flex: 1 1 350px;
            max-width: 500px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
            background-color: #f0f2f5;
            font-size: 1rem;
        }

        .btn-signup {
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 25px;
            font-weight: 500;
            background-color: #357edd;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-signup:hover {
            background-color: #245cbf;
        }

        .login-link {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 15px;
            color: #6c757d;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .image-container {
            flex: 1 1 300px;
            max-width: 400px;
            height: 100%;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 12px;
        }

        .btn-register-dorm {
            margin-top: 20px;
            padding: 15px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            background: linear-gradient(135deg, #adc7ea 0%, #007bff 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px #abcdf1(102, 126, 234, 0.4);
            width: 100%;
        }

        .btn-register-dorm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px #16579e(102, 126, 234, 0.6);
            
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: left;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .amenity-item:hover {
            border-color: #667eea;
            background-color: #f0f4ff;
        }

        .amenity-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        textarea {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
            background-color: #f0f2f5;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            resize: vertical;
            min-height: 100px;
        }

        select {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
            background-color: #f0f2f5;
            font-size: 1rem;
            cursor: pointer;
        }

        /* لتناسب الشاشات الصغيرة */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                align-items: center;
            }

            .image-container {
                max-width: 100%;
                height: auto;
            }

            .image-container img {
                height: auto;
                max-height: 300px;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav ul {
                flex-direction: column;
                margin-top: 10px;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <header>
        <a href="home_page.php" class="logo">MU-DORMS</a>
        <nav>
            <ul>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="dorm_type.php">Dorms</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </nav>
        <a href="log_in_student.php" class="login-btn">Log In</a>
    </header>

    <div class="main-container">
        <div class="form-container">
            <h2>Create your account</h2>
            <form action="create_account_process.php" method="post">
                <div>
                    <label for="fullname">Full Name</label>
                    <br>
                    <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
                </div>
                <div>
                    <label for="email">Email Address</label>
                    <br>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div>
                    <label for="password">Password</label>
                    <br>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div>
                    <label for="confirm">Confirm Password</label>
                    <br>
                    <input type="password" id="confirm" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <a href="log_in_student.php"><button type="submit" class="btn-signup">Sign Up</button></a>
            </form>
            <div class="login-link">
                Already have an account? <a href="login_options.php">Log in</a>
            </div>
        </div>
        <div class="image-container">
            <img src="data:image/jpeg;base64,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" alt="Dorm Image">

    <a href="register_dorm_owner.php" style="text-decoration: none;">
        <button type="button" class="btn-register-dorm">
            Register as Dorm Owner
        </button>
    </a>
</div>
        </div>
    </div>

    <script>
    const nameInput = document.querySelector('input[name="fullname"]');

    if (nameInput) {
        nameInput.addEventListener('input', function() {
           
            this.value = this.value.replace(/[^a-zA-Z\u0600-\u06FF\s]/g, '');
        });
    }
</script>
</body>
</html>