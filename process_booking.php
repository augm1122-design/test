<?php
// Start the session at the very beginning of the script
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file PDO
require_once 'conn.php'; 

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and receive data from the form
    // Using null coalescing operator (??) to prevent undefined index warnings if a field is missing
    $dorm_id = filter_var($_POST['dorm_id'] ?? null, FILTER_VALIDATE_INT);
    $room_type = htmlspecialchars($_POST['room_type'] ?? '', ENT_QUOTES, 'UTF-8');
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT); 
    $full_name = htmlspecialchars($_POST['full_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8');
    $start_date = htmlspecialchars($_POST['start_date'] ?? '', ENT_QUOTES, 'UTF-8');
    $national_id = htmlspecialchars($_POST['national_id'] ?? '', ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars($_POST['gender'] ?? '', ENT_QUOTES, 'UTF-8');
    $booking_duration_months = filter_var($_POST['booking_duration_months'] ?? null, FILTER_VALIDATE_INT);
    $payment_method = htmlspecialchars($_POST['payment_method'] ?? '', ENT_QUOTES, 'UTF-8');
    $specialty = htmlspecialchars($_POST['specialty'] ?? '', ENT_QUOTES, 'UTF-8');
    $booking_type = htmlspecialchars($_POST['booking_type'] ?? 'full_room', ENT_QUOTES, 'UTF-8');
    $selected_roommate_id = filter_var($_POST['selected_roommate_id'] ?? null, FILTER_VALIDATE_INT);
    $room_id = filter_var($_POST['room_id'] ?? null, FILTER_VALIDATE_INT);

    // Basic validation
    // The previous issue was likely that one of these filtered variables returned false or was truly empty.
    // Ensure all required fields are validated properly.
    $errors = [];

    if (!$dorm_id) { $errors[] = "Dorm ID is missing or invalid."; }
    if (!$price) { $errors[] = "Price is missing or invalid."; }
    if (!$email) { $errors[] = "Email is missing or invalid."; }
    if (!$booking_duration_months || $booking_duration_months <= 0) { $errors[] = "Booking duration is missing or invalid."; }
    if (empty($full_name)) { $errors[] = "Full Name is required."; }
    if (empty($phone)) { $errors[] = "Phone Number is required."; }
    if (empty($start_date)) { $errors[] = "Start Date is required."; }
    if (empty($national_id)) { $errors[] = "National/University ID is required."; }
    if (empty($gender)) { $errors[] = "Gender is required."; }
    if (empty($payment_method)) { $errors[] = "Payment Method is required."; }

    // If there are any errors, store them and redirect back
    if (!empty($errors)) {
        $_SESSION['booking_error'] = implode("<br>", $errors); // Join errors with line breaks
        // Ensure dorm_id and room_type are available to redirect properly
        $redirect_params = '';
        if (isset($_POST['dorm_id']) && isset($_POST['room_type'])) {
            $redirect_params = "?dorm_id=" . $_POST['dorm_id'] . "&room_type=" . urlencode($_POST['room_type']);
        }
        header("Location: booking_page.php" . $redirect_params);
        exit();
    }

    // Calculate check-out date
    $check_out_date = date('Y-m-d', strtotime("+$booking_duration_months months", strtotime($start_date)));

    // Calculate total price for the booking
    $total_price = (float)$price * (int)$booking_duration_months;

    try {
        // Start a transaction to ensure data consistency
        $pdo->beginTransaction();

        // Check if the student has a pre-existing login (via email)
        $stmt_check_user = $pdo->prepare("SELECT users_id FROM login WHERE email = :email");
        $stmt_check_user->execute([':email' => $email]);
        $existing_user = $stmt_check_user->fetch(PDO::FETCH_ASSOC);

        if (!$existing_user) {
            // If no account found with this email
            $_SESSION['booking_error'] = "You must have a registered account with this email address to confirm your reservation. Please log in or register first.";
            $pdo->rollBack(); 
            $redirect_params = '';
            if (isset($_POST['dorm_id']) && isset($_POST['room_type'])) {
                $redirect_params = "?dorm_id=" . $_POST['dorm_id'] . "&room_type=" . urlencode($_POST['room_type']);
            }
            header("Location: booking_page.php" . $redirect_params); // Redirect back
            exit();
        }

        $student_id = $existing_user['users_id']; // Get the existing student's ID

        // Update user's specialty if provided
        if (!empty($specialty)) {
            $stmt_update_specialty = $pdo->prepare("UPDATE login SET specialty = :specialty WHERE users_id = :user_id");
            $stmt_update_specialty->execute([':specialty' => $specialty, ':user_id' => $student_id]);
        }

        // Determine if looking for roommate
        $looking_for_roommate = ($booking_type === 'shared_spot' && empty($selected_roommate_id)) ? 1 : 0;

        // Insert all booking data (student and booking details) into the 'booking' table
        $stmt_booking = $pdo->prepare("
            INSERT INTO booking
            (dorm_id, student_id, room_type, room_id, check_in_date, check_out_date,
             booking_duration_months, total_price, payment_method, status,
             full_name, email, national_id, gender, phone,
             booking_type, looking_for_roommate)
            VALUES
            (:dorm_id, :student_id, :room_type, :room_id, :check_in_date, :check_out_date,
             :booking_duration_months, :total_price, :payment_method, 'Pending',
             :full_name, :email, :national_id, :gender, :phone,
             :booking_type, :looking_for_roommate)
        ");
        
        $stmt_booking->execute([
            ':dorm_id' => $dorm_id,
            ':student_id' => $student_id,
            ':room_type' => $room_type,
            ':room_id' => $room_id,
            ':check_in_date' => $start_date,
            ':check_out_date' => $check_out_date,
            ':booking_duration_months' => $booking_duration_months,
            ':total_price' => $total_price,
            ':payment_method' => $payment_method,
            ':full_name' => $full_name,
            ':email' => $email,
            ':national_id' => $national_id,
            ':gender' => $gender,
            ':phone' => $phone,
            ':booking_type' => $booking_type,
            ':looking_for_roommate' => $looking_for_roommate
        ]);

        $booking_id = $pdo->lastInsertId();

        // If booking type is shared_spot, create roommate request
        if ($booking_type === 'shared_spot') {
            // If a specific roommate was selected
            if ($selected_roommate_id) {
                // Find the roommate's active request
                $stmt_find_request = $pdo->prepare("
                    SELECT request_id FROM roommate_requests
                    WHERE student_id = :roommate_id
                    AND dorm_id = :dorm_id
                    AND room_type = :room_type
                    AND status = 'active'
                    LIMIT 1
                ");
                $stmt_find_request->execute([
                    ':roommate_id' => $selected_roommate_id,
                    ':dorm_id' => $dorm_id,
                    ':room_type' => $room_type
                ]);
                $roommate_request = $stmt_find_request->fetch(PDO::FETCH_ASSOC);

                if ($roommate_request) {
                    // Create a match
                    $stmt_create_match = $pdo->prepare("
                        INSERT INTO roommate_matches
                        (request_id_1, request_id_2, student_id_1, student_id_2, room_id, booking_id_2, match_status)
                        VALUES
                        (:request_id_1, :request_id_2, :student_id_1, :student_id_2, :room_id, :booking_id_2, 'pending')
                    ");

                    // We need to create a request for current student first
                    $stmt_create_request = $pdo->prepare("
                        INSERT INTO roommate_requests
                        (student_id, dorm_id, room_id, room_type, specialty, gender, status)
                        VALUES
                        (:student_id, :dorm_id, :room_id, :room_type, :specialty, :gender, 'matched')
                    ");
                    $stmt_create_request->execute([
                        ':student_id' => $student_id,
                        ':dorm_id' => $dorm_id,
                        ':room_id' => $room_id,
                        ':room_type' => $room_type,
                        ':specialty' => $specialty,
                        ':gender' => $gender
                    ]);
                    $current_request_id = $pdo->lastInsertId();

                    // Create the match
                    $stmt_create_match->execute([
                        ':request_id_1' => $roommate_request['request_id'],
                        ':request_id_2' => $current_request_id,
                        ':student_id_1' => $selected_roommate_id,
                        ':student_id_2' => $student_id,
                        ':room_id' => $room_id,
                        ':booking_id_2' => $booking_id
                    ]);

                    // Update the other student's request to matched
                    $stmt_update_request = $pdo->prepare("
                        UPDATE roommate_requests SET status = 'matched' WHERE request_id = :request_id
                    ");
                    $stmt_update_request->execute([':request_id' => $roommate_request['request_id']]);
                }
            } else {
                // No specific roommate selected, create an active request
                $stmt_create_request = $pdo->prepare("
                    INSERT INTO roommate_requests
                    (student_id, dorm_id, room_id, room_type, specialty, gender, status)
                    VALUES
                    (:student_id, :dorm_id, :room_id, :room_type, :specialty, :gender, 'active')
                ");
                $stmt_create_request->execute([
                    ':student_id' => $student_id,
                    ':dorm_id' => $dorm_id,
                    ':room_id' => $room_id,
                    ':room_type' => $room_type,
                    ':specialty' => $specialty,
                    ':gender' => $gender
                ]);
            }
        }

        // Commit the transaction if all operations are successful
        $pdo->commit();

        // Redirect to thanks.php upon success
        header("Location: thanks.php");
        exit(); 

    } catch (PDOException $e) {
        // Rollback the transaction in case of any error
        $pdo->rollBack();
        // Store the error message in the session
        $_SESSION['booking_error'] = "An error occurred during booking confirmation: " . $e->getMessage();
        // Redirect back to the booking page with original parameters
        $redirect_params = '';
        if (isset($_POST['dorm_id']) && isset($_POST['room_type'])) {
            $redirect_params = "?dorm_id=" . $_POST['dorm_id'] . "&room_type=" . urlencode($_POST['room_type']);
        }
        header("Location: booking_page.php" . $redirect_params);
        exit();
    }
} else {
    // If the request is not POST, redirect or display an error
    $_SESSION['booking_error'] = "Invalid request method.";
    header("Location: booking_page.php"); // Redirect to booking page, without parameters
    exit();
}
?>