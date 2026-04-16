<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Options | MU-DORMS</title>  <!-- عنوان الصفحة الذي يظهر في المتصفح -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* for body */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #212529;
    }
    /* for header */
    header {
      background-color: #fff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
     
    /* تصف تصميم الشعار وقائمة التنقل والروابط داخلها */
    /* الشعار */
    .logo { 
      font-weight: 700;
      font-size: 1.5rem;
      color: #000;
      text-decoration: none;
    }
    /* قائمة التنقل */
    nav ul {
      display: flex;
      list-style: none;
      gap: 30px;
    }
    /* الروابط داخل قائمة التنقل */
    nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: color 0.3s;
    }
    /* الروابط داخل قائمة التنقل عند التمرير عليها */
    nav ul li a:hover {
      color: #007bff;
    }
    /* زر التسجيل */
    .sign-up-btn {
      padding: 8px 20px;
      background-color: #357edd;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 500;
      color: #fcfcfcff;
      transition: background-color 0.3s;
    }
    /* زر التسجيل عند التمرير عليها */
    .sign-up-btn:hover {
      background-color: #245cbf;
    }
    /* للمحتوى */
    .container {
      text-align: center;
      padding: 100px 20px;
    }
    /* عنوان الصفحة */
    h1 {
      margin-bottom: 40px;
      font-size: 2rem;
    }
    /* أزرار تسجيل الدخول */
    .login-button {
      display: block;
      width: 300px;
      margin: 15px auto;
      padding: 15px;
      background-color: #357edd;
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s;
      text-decoration: none;
    }
    /* أزرار تسجيل الدخول عند التمرير عليها */
    .login-button:hover {
      background-color: #245cbf;
    }
    /*لتناسب الشاشات الصغيرة مثل الهواتف */
    @media (max-width: 500px) {
      .login-button {
        width: 90%;
      }

      header {
        flex-direction: column;
        align-items: flex-start;
      }

      nav ul {
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>

  <header>
    <a href="home_page.php" class="logo">MU-DORMS</a>
    <nav>
      <ul>
        <li><a href="about.php">About</a></li>
        <li><a href="dorm_type.php">Dorms</a></li>
      </ul>
    </nav>
    <a href="create_account.php" class="sign-up-btn">Sign Up</a>
  </header>

  <div class="container">
    <h1>Choose Your Login Type</h1>
    <a href="log_in_admin.php" class="login-button">Log In as Admin</a>
    <a href="log_in_student.php" class="login-button">Log In as Student</a>
    <a href="log_in_dorm_owner.php" class="login-button">Log In as Dorm Owner</a>
  </div>

</body>
</html>
