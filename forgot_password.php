<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conn.php'; 
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['status' => 'error', 'message' => '  Please enter a valid email address.'];
    
      } else {
        try {
            $stmt = $pdo->prepare("SELECT email FROM login WHERE email = ?");
            $stmt->execute([$email]);
            $user_exists = $stmt->fetch();

            if ($user_exists) {
                $token = bin2hex(random_bytes(32));
                
                date_default_timezone_set('Asia/Amman'); 

                $expires = date("Y-m-d H:i:s", strtotime('+30 minutes'));

                $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ? AND used = 0");
                $stmt->execute([$email]);

                
                $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$email, $token, $expires]);

                $resetLink = "http://localhost/graduation project 2/reset_password.php?token=" . $token . "&email=" . urlencode($email);//urlencode() يستخدم لتشفير البريد الإلكتروني ليصبح صالحًا للاستخدام في الرابط

                
                $response = ['status' => 'success', 'message' => ' A password reset link has been sent to your email. Please check your inbox. ', 'debug_link' => $resetLink]; 

            } else {
                $response = ['status' => 'error', 'message' => 'This email is not registered with us.'];
            }
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'A database error occurred: ' . $e->getMessage()];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Forgot Password | MU-DORMS</title>
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

   header 
    {
       background-color: #fff; 
       padding: 20px 40px;
        display: flex;
         justify-content: space-between;
          align-items: center; 
          box-shadow: 0 2px 4px rgba(0,0,0,0.05);
         }

   .logo
    {
       font-weight: 700;
        font-size: 1.5rem; 
        text-decoration: none;
         color: #000; 
        }

   nav ul 
   {
     list-style: none;
      display: flex; gap: 30px;
     }

   nav ul li a
    {
       text-decoration: none;
        color: #333;
         font-weight: 500;
          transition: color 0.3s; 
        }

   nav ul li a:hover 
   {
     color: #007bff; 
    }

   .sign-up-btn 
   {
     padding: 8px 20px; 
     background-color: #357edd; 
     border-radius: 25px;
      text-decoration: none; 
      font-weight: 500;
       color: #f9f6f6ff;
       }
    .sign-up-btn:hover
   {
     background-color: #245cbf;
   }

   .main-container
    {
       display: flex;
        flex-wrap: wrap;
         justify-content: center; 
         align-items: flex-start;
          padding: 60px 20px;
           max-width: 1200px;
            margin: auto; 
            gap: 40px; 
            min-height: calc(100vh - 80px); 
          }

   .form-container 
   {
    flex: 1 1 350px;
     max-width: 450px;
     background-color: #fff;
     padding: 40px;
     border-radius: 12px;
     box-shadow: 0 4px 8px rgba(0,0,0,0.05);
   }
   .form-container h2 
   { 
    margin-bottom: 30px;
     font-size: 1.8rem; 
     text-align: center; 
    }

   form 
   { 
    display: flex;
     flex-direction: column;
      gap: 15px;
     }

   input[type="email"] 
   {
     padding: 12px 15px;
      border-radius: 12px; 
      border: 1px solid #dcdcdc;
       background-color: #f0f2f5; 
       font-size: 1rem;
        width: 100%; 
      }

   .btn 
   { 
    padding: 12px; 
    font-size: 1rem;
     border: none; 
     border-radius: 25px;
      font-weight: 500;
       cursor: pointer;
        transition: background-color 0.3s;
         text-align: center;
          display: block;
           width: 100%; 
          }

   .btn-primary 
   {
     background-color: #357edd;
      color: white;
     }

   .btn-primary:hover 
   {
     background-color: #245cbf; 
    }

   .back-link
    {
       text-align: center;
        font-size: 0.9rem;
         margin-top: 20px;
          color: #6c757d; 
        }

   .back-link a 
   { 
    color: #007bff; 
    text-decoration: none;
   }

   .message 
   { 
    text-align: center;
     margin-top: 20px;
      padding: 10px;
       border-radius: 8px; 
       font-size: 0.9rem; 
      }

   .message.success 
   {
     background-color: #d4edda;
     color: #155724; 
     border: 1px solid #c3e6cb; 
    }

   .message.error
    { 
      background-color: #f8d7da;
       color: #721c24; 
       border: 1px solid #f5c6cb;
       }

   @media (max-width: 768px) 
   {
   .main-container
    {
       flex-direction: column;
       align-items: center; 
      }

   header 
   {
     flex-direction: column;
      align-items: flex-start;
     }

   nav ul 
   {
     flex-direction: column;
      margin-top: 10px; gap: 10px; 
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
 <li><a href="contact.php">Contact</a></li>
 </ul>
 </nav>
 <a href="create_account.php" class="sign-up-btn">Sign Up</a>
 </header>

 <div class="main-container">
 <div class="form-container">
 <h2>Forgot your password?</h2>
 <p style="text-align: center; margin-bottom: 20px; color: #6c757d;">Please enter your email to reset your password.</p>
 <form id="forgotPasswordForm">
   <b>Email</b>
   <input type="email" id="email" name="email" placeholder="Enter your email" required>
   <button class="btn btn-primary" type="submit">Send Reset Link</button>
 </form>
 <div id="responseMessage" class="message" style="display:none;"></div>
 <div class="back-link">
 Remembered your password? <a href="log_in_student.php">Back to Sign In.</a>
 </div>
 </div>
 </div>

 <script>
   document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
     event.preventDefault(); 

     const email = document.getElementById('email').value.trim();
     const responseMessageDiv = document.getElementById('responseMessage');

     if (email) {
       fetch('forgot_password.php', {
         method: 'POST',
         headers: {
           'Content-Type': 'application/x-www-form-urlencoded',
         },
         body: new URLSearchParams({
           email: email,
         }).toString()
       })
       .then(response => response.json())
       .then(data => {
         responseMessageDiv.style.display = 'block';
         responseMessageDiv.textContent = data.message;
         responseMessageDiv.className = 'message ' + data.status;

         if (data.status === 'success') {
             if (data.debug_link) {
                 responseMessageDiv.innerHTML += `<br><small>Debug link (for development only): <a href="${data.debug_link}" target="_blank">${data.debug_link}</a></small>`;
             }
         }
       })
       .catch(error => {
         console.error('Error:', error);
         responseMessageDiv.style.display = 'block';
         responseMessageDiv.textContent = 'An unexpected error occurred. Please try again.';
         responseMessageDiv.className = 'message error';
       });
     } else {
       responseMessageDiv.style.display = 'block';
       responseMessageDiv.textContent = 'Please enter an email address.';
       responseMessageDiv.className = 'message error';
     }
   });
 </script>

</body>
</html>