<?php
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dorm_id = intval($_GET['id']); 

    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "graduation";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_dorm_details = "SELECT d.*, GROUP_CONCAT(a.amenity_name SEPARATOR ', ') AS amenities_list
                         FROM dorms d
                         LEFT JOIN dorm_amenities da ON d.dorm_id = da.dorm_id
                         LEFT JOIN amenities a ON da.amenity_id = a.amenity_id
                         WHERE d.dorm_id = ?
                         GROUP BY d.dorm_id";
    $stmt_dorm_details = $conn->prepare($sql_dorm_details);
    $stmt_dorm_details->bind_param("i", $dorm_id);
    $stmt_dorm_details->execute();
    $result_dorm_details = $stmt_dorm_details->get_result();

    if ($result_dorm_details->num_rows > 0) {
        $dorm = $result_dorm_details->fetch_assoc();
        $dorm_name = htmlspecialchars($dorm['name']);
        $dorm_description = htmlspecialchars($dorm['description']);
        $dorm_location = htmlspecialchars($dorm['location']);
        $dorm_image = htmlspecialchars($dorm['image_url']);
        $dorm_owner_name = htmlspecialchars($dorm['owner_name']);
        $dorm_contact_email = htmlspecialchars($dorm['contact_email']);
        $dorm_contact_phone = htmlspecialchars($dorm['contact_phone']);
        $dorm_proximity_to_university = htmlspecialchars($dorm['proximity_to_university']);
        $dorm_number_of_rooms = htmlspecialchars($dorm['number_of_rooms']);
        $amenities_list = $dorm['amenities_list'];
    } else {
        $dorm_name = "Dorm Not Found";
        $dorm_description = "The requested dorm could not be found.";
        $dorm_location = "";
        $dorm_image = "";
        $dorm_owner_name = "";
        $dorm_contact_email = "";
        $dorm_contact_phone = "";
        $dorm_proximity_to_university = "";
        $dorm_number_of_rooms = "";
        $amenities_list = "";
    }
    $stmt_dorm_details->close();


    $sql_room_prices = "SELECT room_type, price FROM dorm_room_prices WHERE dorm_id = ?";
    $stmt_room_prices = $conn->prepare($sql_room_prices);
    $stmt_room_prices->bind_param("i", $dorm_id);
    $stmt_room_prices->execute();
    $result_room_prices = $stmt_room_prices->get_result();
    $room_prices = [];
    if ($result_room_prices->num_rows > 0) {
        while ($row = $result_room_prices->fetch_assoc()) {
            $room_prices[] = $row;
        }
    }
    $stmt_room_prices->close();
    $conn->close();

} else {
    $dorm_name = "Invalid Request";
    $dorm_description = "Please select a dorm to view its details.";
    $dorm_location = "";
    $dorm_image = "";
    $dorm_owner_name = "";
    $dorm_contact_email = "";
    $dorm_contact_phone = "";
    $dorm_proximity_to_university = "";
    $dorm_number_of_rooms = "";
    $amenities_list = "";
    $room_prices = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-DORMS | Dorm Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background-color: var(--card-background);
            border-bottom: 1px solid var(--medium-grey);
            box-shadow: 0 2px 4px rgba(13, 13, 14, 0.05);
        }

        .navbar .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: black;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #060606ff;
            margin-left: 30px;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #007bffس;
        }
        .sign-in-btn, .log-out-btn , .profile-btn {
            background-color: #007bff;
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

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .dorm-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .dorm-header h1 {
            font-size: 3em;
            color: #2c3e50;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }
        .dorm-header p {
            color: #666;
            font-size: 1.2em;
            font-style: italic;
        }
        .dorm-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .dorm-images img {
            width: 100%;
            height: 280px;
            object-fit: contain;
            clip-path: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease, border-radius 0.4s ease, box-shadow 0.4s ease;
            background-color: #ffffff;
        }
        .dorm-images img:hover {
            transform: scale(1.02);
        }
        .dorm-details-section {
            margin-bottom: 40px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        .dorm-details-section h2 {
            color: #007BFF;
            margin-bottom: 20px;
            font-size: 2.2em;
            border-bottom: 3px solid #007BFF;
            padding-bottom: 8px;
            display: inline-block;
            font-weight: 600;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .detail-item {
            background-color: #f8faff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e3e9f3;
            display: flex;
            align-items: center;
            font-size: 1.1em;
            color: #444;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .detail-item strong {
            color: #2c3e50;
            margin-right: 10px;
            font-size: 1.05em;
        }
        .detail-item i {
            margin-right: 10px;
            color: #007BFF;
            font-size: 1.2em;
        }
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .amenity-item {
            background-color: #e9f5ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            font-size: 1em;
            color: #0056b3;
            transition: background-color 0.3s ease;
        }
        .amenity-item:hover {
            background-color: #d1e7ff;
        }
        .amenity-item i {
            margin-right: 10px;
            color: #007BFF;
        }
        .location-link {
            text-align: center;
            margin-top: 30px;
        }
        .location-link a {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        .location-link a:hover {
            background-color: #218838;
        }
        .room-types-prices {
            margin-top: 30px;
        }
        .room-types-prices h3 {
            color: #007BFF;
            margin-bottom: 15px;
            font-size: 1.7em;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
            display: inline-block;
            font-weight: 600;
        }
        .room-item {
            background-color: #fefefe;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            margin-bottom: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .room-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        }
        .clickable-room-type {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .clickable-room-type:hover {
            background-color: #f8f9fa;
        }
        .room-item strong {
            font-size: 1.2em;
            color: #007BFF;
            transition: color 0.3s ease;
        }
        .clickable-room-type:hover strong {
            color: #0056b3;
        }
        .room-item span {
            font-size: 1.2em;
            color: red;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 15px 20px;
            }
            .nav-links {
                margin-top: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }
            .nav-links a {
                margin: 5px 15px;
            }
            .container {
                margin: 15px;
                padding: 20px;
            }
            .dorm-header h1 {
                font-size: 2em;
            }
            .dorm-header p {
                font-size: 1em;
            }
            .dorm-images {
                grid-template-columns: 1fr;
            }
            .details-grid {
                grid-template-columns: 1fr;
            }
            .amenity-item {
                padding: 8px 10px;
                font-size: 0.9em;
            }
        }

        .comments-section {
            margin-top: 50px;
            padding: 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }

        .comments-header h2 {
            font-size: 2em;
            color: #2c3e50;
            margin: 0;
        }

        .rating-summary {
            text-align: right;
        }

        .avg-rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-number {
            font-size: 2.5em;
            font-weight: 700;
            color: #007bff;
        }

        .avg-rating .stars {
            font-size: 1.2em;
            color: #ffc107;
        }

        .total-reviews {
            color: #666;
            font-size: 0.9em;
        }

        .add-comment-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
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
            min-height: 100px;
        }

        .char-count {
            display: block;
            text-align: right;
            color: #666;
            font-size: 0.85em;
            margin-top: 5px;
        }

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

        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .comment-item {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .no-comments {
            text-align: center;
            color: #999;
            font-size: 1.1em;
            padding: 40px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #007bff;
            font-size: 1.2em;
        }

        .loading i {
            margin-right: 10px;
        }

        .load-more-container {
            text-align: center;
            margin-top: 30px;
        }

        .load-more-btn {
            background: white;
            color: #007bff;
            padding: 12px 40px;
            border: 2px solid #007bff;
            border-radius: 8px;
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

        @media (max-width: 768px) {
            .comments-section {
                padding: 20px;
            }

            .comments-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .rating-summary {
                text-align: left;
            }

            .add-comment-form {
                padding: 20px;
            }

            .comment-item {
                padding: 15px;
            }

            .author-avatar {
                width: 40px;
                height: 40px;
                font-size: 1.2em;
            }
        }

        #languageToggle {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        #languageToggle:hover {
            background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.5);
            transform: translateY(-2px);
        }
    </style>
    <link rel="stylesheet" href="rtl-support.css">
</head>
<body>
    <button id="languageToggle">العربية</button>

    <nav class="navbar">
        <div><a href="home_page.php" class="logo">MU-DORMS</a></div>
        <div class="nav-links">
            <a href="female_dorms.php" data-translate="female dorms">Female Dorms</a>
            <a href="male_dorms.php" data-translate="male_dorms">Male Dorms</a>
            <a href="about.php" data-translate="about">About</a>
            <a href="contact.php" data-translate="contact">Contact</a>
        </div>
    <div class="auth-buttons">
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="user_profile.php" class="profile-btn">
                <i class="fas fa-user"></i> 
            </a>
            <a href="logout.php" class="log-out-btn">Log Out</a>
        <?php else: ?>
            <a href="log_in_student.php" class="sign-in-btn">Sign In</a>
        <?php endif; ?>
        </div>
        </nav>
    

    <div class="container">
        <?php if ($dorm_name != "Invalid Request" && $dorm_name != "Dorm Not Found"): ?>
            <div class="dorm-header">
    <h1><?php echo $dorm_name; ?></h1>
    <p><span data-translate="location_label">Location:</span> <?php echo $dorm_location; ?></p>
</div>
            <div class="dorm-images">
                <?php
                if (!empty($dorm_image)) {
                    echo '<img src="' . $dorm_image . '" alt="Dorm Image">';
                } else {
                    echo '<img src="https://via.placeholder.com/400x280?text=No+Image+Available" alt="No Image">';
                }
                ?>
            </div>
            <div class="dorm-details-section">
                <h2 data-translate="details">Details</h2>
              <div class="details-grid">
    <div class="detail-item">
        <i class="fas fa-info-circle"></i>
        <strong data-translate="description_label">Description:</strong> <?php echo nl2br($dorm_description); ?>
    </div>

    <div class="detail-item">
        <i class="fas fa-user-tie"></i> 
        <strong data-translate="owner_label">Owner:</strong> <?php echo $dorm_owner_name; ?>
    </div>

    <div class="detail-item">
        <i class="fas fa-map-marker-alt"></i> 
        <strong data-translate="proximity_label">Proximity to University:</strong> <?php echo $dorm_proximity_to_university; ?>
    </div>
    
    <div class="detail-item">
        <i class="fas fa-bed"></i>
        <strong data-translate="number_of_rooms_label">Number of Rooms:</strong> <?php echo $dorm_number_of_rooms; ?>
    </div>
</div>
            </div>

            <?php if (!empty($room_prices)): ?>
                <div class="dorm-details-section room-types-prices">
                    <h2 data-translate="room_types_and_prices">Room Types and Prices</h2>
                    <?php foreach ($room_prices as $room): ?>
                        <div class="room-item">
                            <a href="booking_page.php?dorm_id=<?php echo $dorm_id; ?>&room_type=<?php echo urlencode($room['room_type']); ?>" class="clickable-room-type">
                                <strong><?php echo $room['room_type']; ?></strong>
<span><?php echo $room['price']; ?> <span data-translate="price_per_month">JOD/Month</span></span>                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dorm-details-section room-types-prices">
                    <h2 data-translate="room_types_and_prices">Room Types and Prices</h2>
                    <p data-translate="no_amenities">No room types or prices listed for this dorm.</p>
                </div>
            <?php endif; ?>
 
            <div class="dorm-details-section">
                <h2 data-translate="amenities">Amenities</h2>
                <div>
                    <?php if (!empty($amenities_list)): ?>
                        <div class="amenities-list">
                            <?php
                            $amenities_array = explode(', ', $amenities_list);
                            foreach ($amenities_array as $amenity_name):
                                $amenity_name = trim($amenity_name);
                                $icon_class = 'fa-info-circle';
                                switch ($amenity_name) {
                                    case 'Wi-Fi':
                                    case 'WIFI':
                                        $icon_class = 'fa-wifi';
                                        break;
                                    case 'Laundry':
                                        $icon_class = 'fa-washer';
                                        break;
                                    case 'Gym':
                                        $icon_class = 'fa-dumbbell';
                                        break;
                                    case 'Parking':
                                        $icon_class = 'fa-parking';
                                        break;
                                    case '24/7 Security':
                                        $icon_class = 'fa-shield-alt';
                                        break;
                                    case 'Kitchen':
                                        $icon_class = 'fa-kitchen-set';
                                        break;
                                    case 'Beds':
                                        $icon_class = 'fa-bed';
                                        break;
                                    case 'Pet friendly':
                                        $icon_class = 'fa-paw';
                                        break;
                                    case 'Pool':
                                        $icon_class = 'fa-swimming-pool';
                                        break;
                                    case 'Private Bathrooms':
                                        $icon_class = 'fa-toilet';
                                        break;
                                    case 'TV':
                                        $icon_class = 'fa-tv';
                                        break;
                                    default:
                                        $icon_class = 'fa-info-circle';
                                        break;
                                }
                                ?>
                                <div class="amenity-item">
                                    <i class="fas <?php echo $icon_class; ?>"></i>
                                    <span><?php echo htmlspecialchars($amenity_name); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p data-translate="no_amenities">No amenities listed for this dorm.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- قسم التعليقات والتقييمات -->
            <div class="comments-section">
                <div class="comments-header">
                    <h2 data-translate="reviews_ratings">Reviews & Ratings</h2>
                    <div class="rating-summary">
                        <div class="avg-rating">
                            <span class="rating-number" id="avgRating">0.0</span>
                            <div class="stars" id="avgStars"></div>
                            <span class="total-reviews" id="totalReviews">(0 reviews)</span>
                        </div>
                    </div>
                </div>

                <!-- نموذج إضافة تعليق -->
                <div class="add-comment-form">
                    <h3 data-translate="write_review">Write a Review</h3>
                    <form id="commentForm">
                        <div class="form-group">
                            <label for="userName" data-translate="your_name">Your Name</label>
                            <input type="text" id="userName" name="name" required maxlength="30" data-translate-placeholder="name_placeholder" placeholder="">
                        </div>

                        <div class="form-group">
                            <label data-translate="your_rating">Your Rating</label>
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
                            <label for="userComment" data-translate="your_review">Your Review</label>
                            <textarea id="userComment" name="comment" required minlength="10" maxlength="500" rows="4" data-translate-placeholder="review_placeholder" placeholder=""></textarea>
                            <small class="char-count"><span id="charCount">0</span>/500</small>
                        </div>

                        <button type="submit" class="submit-btn" data-translate="submit_review">Submit Review</button>
                     </form>
                 </div>

                <!-- قائمة التعليقات -->
                <div class="comments-list" id="commentsList">
                    <div class="loading" id="commentsLoading">
                        <i class="fas fa-spinner fa-spin"></i> <span data-translate="loading">Loading...</span>
                    </div>
                </div>

                <div class="load-more-container" id="loadMoreContainer" style="display: none;">
                    <button class="load-more-btn" id="loadMoreBtn" data-translate="load_more">Load More Reviews</button>
                </div>
            </div>

        <?php else: ?>
            <p style='text-align: center; font-size: 1.2em; color: red;'><?php echo htmlspecialchars($dorm_description); ?></p>
        <?php endif; ?>
    </div>

    <!-- Language Switcher Script -->
    <script src="language-switcher.js"></script>

    <!-- Comments Script -->
    <script>
        const dormId = <?php echo $dorm_id; ?>;
        let commentsOffset = 0;
        const commentsLimit = 5;
        let isLoading = false;

        document.addEventListener('DOMContentLoaded', () => {
            loadComments();
            setupCommentForm();
        });

        // دالة لتحميل التعليقات
        async function loadComments(append = false) {
            if (isLoading) return;
            isLoading = true;

            const loadingEl = document.getElementById('commentsLoading');
            if (!append) {
                loadingEl.style.display = 'block';
            }

            try {
                const response = await fetch(`get_comments.php?dorm_id=${dormId}&offset=${commentsOffset}&limit=${commentsLimit}`);
                const data = await response.json();

                if (data.success) {
                    displayComments(data.comments, append);
                    updateRatingSummary(data.avg_rating, data.total);

                    // إظهار/إخفاء زر "المزيد"
                    const loadMoreContainer = document.getElementById('loadMoreContainer');
                    if (data.has_more) {
                        loadMoreContainer.style.display = 'block';
                    } else {
                        loadMoreContainer.style.display = 'none';
                    }

                    commentsOffset += commentsLimit;
                } else {
                    console.error('Error loading comments:', data.error);
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                loadingEl.style.display = 'none';
                isLoading = false;
            }
        }

        // دالة لعرض التعليقات
        function displayComments(comments, append = false) {
            const commentsList = document.getElementById('commentsList');

            if (!append) {
                commentsList.innerHTML = '';
            }

            if (comments.length === 0 && !append) {
                commentsList.innerHTML = '<p class="no-comments" data-translate="no_reviews">No reviews yet. Be the first to review!</p>';
                return;
            }

            comments.forEach(comment => {
                const commentEl = createCommentElement(comment);
                commentsList.appendChild(commentEl);
            });
        }

        // دالة لإنشاء عنصر تعليق
        function createCommentElement(comment) {
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
                </div>
            `;

            return div;
        }

        // دالة لتحديث ملخص التقييم
        function updateRatingSummary(avgRating, totalReviews) {
            document.getElementById('avgRating').textContent = avgRating.toFixed(1);
            document.getElementById('totalReviews').textContent = `(${totalReviews} ${totalReviews === 1 ? 'review' : 'reviews'})`;

            const avgStarsEl = document.getElementById('avgStars');
            const fullStars = Math.floor(avgRating);
            const hasHalfStar = avgRating % 1 >= 0.5;

            let starsHTML = '';
            for (let i = 0; i < fullStars; i++) {
                starsHTML += '<i class="fas fa-star"></i>';
            }
            if (hasHalfStar) {
                starsHTML += '<i class="fas fa-star-half-alt"></i>';
            }
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                starsHTML += '<i class="far fa-star"></i>';
            }

            avgStarsEl.innerHTML = starsHTML;
        }

        // إعداد نموذج التعليق
        function setupCommentForm() {
            const form = document.getElementById('commentForm');
            const textarea = document.getElementById('userComment');
            const charCount = document.getElementById('charCount');

            // عداد الأحرف
            textarea.addEventListener('input', () => {
                charCount.textContent = textarea.value.length;
            });

            // إرسال النموذج
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = {
                    dorm_id: dormId,
                    name: document.getElementById('userName').value.trim(),
                    rating: parseInt(document.querySelector('input[name="rating"]:checked').value),
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
                        form.reset();
                        charCount.textContent = '0';

                        commentsOffset = 0;
                        loadComments();

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

        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            loadComments(true);
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
