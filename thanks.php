<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #333;
        }

        /* Container for the confirmation message */
        .confirmation-container {
            background-color: #ffffff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 500px;
            width: 90%;
        }

        /* Main heading style */
        .confirmation-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        /* Sub-text styling */
        .confirmation-container p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 2.5rem;
        }

        /* Button styling */
        .return-btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #ffffff;
            background-color: #3498db; /* A blue color */
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        /* Button hover effect */
        .return-btn:hover {
            background-color: #2980b9; /* A darker blue on hover */
            transform: translateY(-2px);
        }

        /* Button active/click effect */
        .return-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <div class="confirmation-container">
        <h1>Thank you for your booking!</h1>
        <p>Your reservation has been confirmed.</p>
        <a href="home_page.php" class="return-btn">Return to Homepage</a>
    </div>

</body>
</html>