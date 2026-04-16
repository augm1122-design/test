<?php

session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-DORMS - My favorite dorms</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       
        body {
            font-family: 'Inter', sans-serif; 
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #fff;
            padding: 1rem 0;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #080808ff;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #007bff;
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
        .favorite-nav-btn {
            color: #333;
            font-size: 1.2rem;
            margin-left: 10px;
            text-decoration: none;
        }
        .favorite-nav-btn:hover {
            color: #007bff;
        }

        .favorites-hero {
            background-color: #ffffffff;
            color: black;
            text-align: center;
            padding: 40px 20px;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .favorites-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dorm-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .dorm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .dorm-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .dorm-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .dorm-info h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #007bff;
            font-size: 1.5rem;
        }
        .dorm-info p {
            margin-bottom: 5px;
            color: #666;
            font-size: 0.95rem;
        }
        .dorm-info p strong {
            color: #333;
        }

        .amenities-list {
            font-size: 0.85rem;
            color: #666;
            margin-top: 10px;
            max-height: 4.2em; 
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }

        .remove-favorite-btn {
            background-color: #dc3545; 
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 10px;
            align-self: flex-start; /* Align button to the left */
            transition: background-color 0.3s ease;
        }
        .remove-favorite-btn:hover {
            background-color: #c82333;
        }
        .no-favorites-message {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            grid-column: 1 / -1; /* Make it span all columns */
        }
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            background-color: #343a40;
            color: white;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <a href="home_page.php" class="logo">MU-DORMS</a>
        <nav>
            <ul>
                <li><a href="dorm_type.php">Dorm</a></li>
                <li><a href="home_page.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
         <div class="auth-buttons">
            <!-- يتحقق من حالة تسجيل الدخول -->
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="user_profile.php" class="profile-btn">
                <i class="fas fa-user"></i> 
            </a>
            <a href="logout.php" class="sign-in-btn">Log Out</a>
        <?php else: ?>
            <a href="log_in_student.php" class="sign-in-btn">Sign In</a>
        <?php endif; ?>
        </div>
    </header>

    <div class="favorites-hero">
        My Favorite Dorms
    </div>

    <div class="favorites-container" id="favorites-listings">
        <div class="no-favorites-message" id="no-favorites-message" style="display: none;">
            You haven't added any dorms to your favorites yet.
        </div>
    </div>

    <footer>
        <p>&copy; 2024 MU-DORMS. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const favoritesListingsDiv = document.getElementById('favorites-listings');
            const noFavoritesMessage = document.getElementById('no-favorites-message');

            const fetchFavorites = async () => {
                try {
                    const response = await fetch('fetch_favorites.php');
                    const data = await response.json();

                    favoritesListingsDiv.innerHTML = ''; // Clear existing listings

                    if (data.success && data.dorms.length > 0) {
                        noFavoritesMessage.style.display = 'none';
                        data.dorms.forEach(dorm => {
                            const dormCard = document.createElement('div');
                            dormCard.classList.add('dorm-card');

                            const imageUrl = dorm.image_url ? dorm.image_url : 'https://via.placeholder.com/300x200?text=No+Image';

                            dormCard.innerHTML = `
                                <a href="dorm_details.php?id=${dorm.dorm_id}">
                                <img src="${imageUrl}" alt="${dorm.name}">
                                </a>
                                <div class="dorm-info">
                                    <h3>${dorm.name}</h3>
                                    <p><strong>Gender:</strong> ${dorm.gender}</p>
                                    <p><strong>Location:</strong> ${dorm.location}</p>
                                    ${dorm.prices_details ? `<p><strong>Prices:</strong> ${dorm.prices_details}</p>` : ''}
                                    ${dorm.room_types_available ? `<p><strong>Room Types:</strong> ${dorm.room_types_available}</p>` : ''}
                                    <p class="amenities-list"><strong>Amenities:</strong> ${dorm.dorm_amenities_list || 'N/A'}</p>
                                    <button class="remove-favorite-btn" data-dorm-id="${dorm.dorm_id}">
                                        <i class="fas fa-trash-alt"></i> Remove from Favorites
                                    </button>
                                </div>
                            `;
                            favoritesListingsDiv.appendChild(dormCard);
                        });

                        // Attach event listeners to remove buttons
                        document.querySelectorAll('.remove-favorite-btn').forEach(button => {
                            button.addEventListener('click', removeFavorite);
                        });

                    } else if (data.success && data.dorms.length === 0) {
                        noFavoritesMessage.style.display = 'block';
                    } else {
                        // Handle cases where success is false (e.g., user not logged in, database error)
                        noFavoritesMessage.style.display = 'block';
                        noFavoritesMessage.textContent = data.message || 'Error fetching favorites. Please try again.';
                    }
                } catch (error) {
                    console.error('Network error fetching favorites:', error);
                    noFavoritesMessage.style.display = 'block';
                    noFavoritesMessage.textContent = 'An error occurred while fetching favorites. Please try again later.';
                }
            };

            const removeFavorite = async (event) => {
                const dormId = event.currentTarget.dataset.dormId;

                const confirmed = confirm('Are you sure you want to remove this dorm from your favorites?');
                if (!confirmed) {
                    return;
                }

                const formData = new FormData();
                formData.append('dorm_id', dormId);
                formData.append('action', 'remove'); 

                try {
                    const response = await fetch('toggle_favorite.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.status === 'not_logged_in') {
                        alert(data.message);
                        window.location.href = 'log_in_student.php'; // Redirect to login
                        return;
                    }

                    if (data.status === 'success') {
                        console.log(data.message);
                        fetchFavorites(); 
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error removing favorite:', error);
                    alert('An error occurred while removing from favorites. Please try again.');
                }
            };

            fetchFavorites(); // Call the function when the page loads
        });
    </script>
</body>
</html>