<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "graduation";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
   if (!preg_match('/^[\p{L}\s]+$/u', $fullname)) {
        echo "<script>alert('Error: Name must contain letters only (الاسم يجب أن يحتوي على حروف فقط).'); window.history.back();</script>";
        exit();
    }
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = 2; 


    if ($password != $confirm_password) {
        echo "Error: Passwords do not match.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO login (name, email, level, password) VALUES (?, ?, ?, ?)");

    
    $stmt->bind_param("ssis", $fullname, $email, $level, $hashed_password);

    if ($stmt->execute()) {
        header("Location: log_in_student.php");
        exit();
        
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>