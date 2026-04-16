<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-DORMS | Booking</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css"> 
    
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #0056b3;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --text-color: #333;
            --border-color: #dee2e6;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 8px rgba(0,0,0,0.15);
            --shadow-lg: 0 8px 16px rgba(0,0,0,0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #66a6ea 0%, #5d8bb6 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--text-color);
        }

        .booking-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .booking-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .booking-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .booking-header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .booking-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 0;
            min-height: 600px;
        }

        .building-section {
            padding: 40px;
            background: #f8f9fa;
            border-right: 1px solid var(--border-color);
        }

        .building-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 1.8em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .building-section h2 i {
            font-size: 0.9em;
        }

        .building-visualizer {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: var(--shadow-md);
        }

        .floor-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .floor-btn {
            flex: 1;
            min-width: 100px;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1em;
            color: var(--text-color);
        }

        .floor-btn:hover {
            border-color: var(--primary-color);
            background: #e7f3ff;
            transform: translateY(-2px);
        }

        .floor-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .floor-btn .floor-info {
            display: block;
            font-size: 0.85em;
            opacity: 0.8;
            margin-top: 5px;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .room-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .room-card.available {
            border-color: var(--success-color);
            background: #f0fff4;
        }

        .room-card.available:hover {
            background: #d4f4dd;
            border-color: #28a745;
        }

        .room-card.occupied {
            border-color: var(--danger-color);
            background: #fff5f5;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .room-card.selected {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .room-number {
            font-size: 1.3em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .room-type {
            font-size: 0.85em;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .room-price {
            font-size: 0.9em;
            font-weight: 600;
            color: var(--primary-color);
        }

        .room-card.selected .room-price {
            color: white;
        }

        .room-status {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .room-status.available {
            background: var(--success-color);
        }

        .room-status.occupied {
            background: var(--danger-color);
        }

        .form-section {
            padding: 40px;
            background: white;
        }

        .form-section h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8em;
        }

        .selected-room-info {
            background: #e7f3ff;
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .selected-room-info h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .selected-room-info p {
            margin: 5px 0;
            color: var(--text-color);
        }

        .selected-room-info strong {
            color: var(--secondary-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.95em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .radio-option:hover {
            border-color: var(--primary-color);
            background: rgba(0, 123, 255, 0.05);
        }

        .radio-option input[type="radio"] {
            width: auto;
            margin-left: 10px;
            cursor: pointer;
        }

        .radio-option input[type="radio"]:checked + span {
            color: var(--primary-color);
            font-weight: 600;
        }

        .roommates-list {
            max-height: 300px;
            overflow-y: auto;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            background: #f8f9fa;
        }

        .roommate-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .roommate-card:hover {
            border-color: var(--primary-color);
            background: rgba(0, 123, 255, 0.05);
            transform: translateX(-3px);
        }

        .roommate-card.selected {
            border-color: var(--success-color);
            background: rgba(40, 167, 69, 0.1);
        }

        .roommate-info {
            flex: 1;
        }

        .roommate-specialty {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .roommate-meta {
            font-size: 0.85em;
            color: #666;
        }

        .roommate-select-icon {
            color: var(--success-color);
            font-size: 1.5em;
            display: none;
        }

        .roommate-card.selected .roommate-select-icon {
            display: block;
        }

        .loading-roommates {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .no-roommates {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .info-text {
            margin-top: 10px;
            padding: 10px;
            background: #e7f3ff;
            border-radius: 6px;
            font-size: 0.9em;
            color: #0c5460;
        }

        .info-text i {
            margin-left: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .legend {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
        }

        .legend-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }

        .legend-dot.available {
            background: var(--success-color);
        }

        .legend-dot.occupied {
            background: var(--danger-color);
        }

        .legend-dot.selected {
            background: var(--primary-color);
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: var(--text-color);
        }

        .loading i {
            font-size: 3em;
            color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }


        @media (max-width: 1024px) {
            .booking-content {
                grid-template-columns: 1fr;
            }

            .building-section {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .booking-header {
                padding: 20px;
            }

            .booking-header h1 {
                font-size: 1.8em;
            }

            .building-section,
            .form-section {
                padding: 20px;
            }

            .rooms-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 10px;
            }
        }

        #languageToggle {
    background-color: #007bff;
    color: white;
    padding: 8px 22px;
    border-radius: 25px; 
    border: none;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    position: fixed; 
    top: 20px;
    right: 20px;
    z-index: 1000;
}
#languageToggle:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}
        .language-toggle i { font-size: 1.2rem; }

        @media (max-width: 768px) {
            .language-toggle { top: 70px; right: 10px; padding: 0.4rem 0.8rem; font-size: 0.9rem; }
        }
    </style>

    <script src="language-switcher.js"></script>
</head>
<body>
    <div class="booking-container">
        <div class="booking-header">
            <h1 language-switch="book_your_room"><i class="fas fa-building"></i> Book Your Room</h1>
            <p language-switch="building_layout">Select your preferred room from our interactive building map</p>
        </div>

        <div class="booking-content">
            <div class="building-section">
                <h2><i class="fas fa-map-marked-alt"></i> Building Layout</h2>

                <div class="building-visualizer">
                    <div id="floorSelector" class="floor-selector">
                        <div class="loading">
                            <i class="fas fa-spinner"></i>
                            <p language-switch="loading_building">Loading building data...</p>
                        </div>
                    </div>

                    <div id="roomsGrid" class="rooms-grid"></div>

                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-dot available"></div>
                            <span language-switch="available">Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot occupied"></div>
                            <span language-switch="occupied">Occupied</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot selected"></div>
                            <span language-switch="selected">Selected</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="fas fa-edit"></i> <span language-switch="book_your_room">Booking Details</span></h2>

                <div id="selectedRoomInfo" class="selected-room-info" style="display: none;">
                    <h3><i class="fas fa-check-circle"></i> <span language-switch="selected_room">Selected Room</span></h3>
                    <p><strong language-switch="label_room">Room:</strong> <span id="infoRoomNumber">-</span></p>
                    <p><strong language-switch="label_type">Type:</strong> <span id="infoRoomType">-</span></p>
                    <p><strong language-switch="label_position">Position:</strong> <span id="infoPosition">-</span></p>
                    <p><strong language-switch="label_view">View:</strong> <span id="infoView">-</span></p>
                    <p><strong language-switch="label_price">Price:</strong> <span id="infoPrice">-</span> JOD/Month</p>
                </div>
                <form id="bookingForm" action="process_booking.php" method="POST">
                    <input type="hidden" name="dorm_id" id="dormId">
                    <input type="hidden" name="room_type" id="roomType">
                    <input type="hidden" name="room_id" id="roomId">
                    <input type="hidden" name="price" id="price">

                    <div class="form-group">
                        <label for="full_name"><i class="fas fa-user"></i> <span language-switch="full_name">Full Name:</span></label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> <span language-switch="email">Email:</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="national_id"><i class="fas fa-id-card"></i> <span language-switch="national_id">National/University ID:</span></label>
                        <input type="text" id="national_id" name="national_id" required>
                    </div>

                    <div class="form-group">
                        <label for="gender"><i class="fas fa-venus-mars"></i> <span language-switch="gender_label">Gender:</span></label>
                        <select id="gender" name="gender" required>
                            <option value="" language-switch="select_gender">Select Gender</option>
                            <option value="Male" language-switch="male">Male</option>
                            <option value="Female" language-switch="female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="specialty"><i class="fas fa-graduation-cap"></i> <span language-switch="specialty_label">Specialty/Major:</span></label>
                        <input type="text" id="specialty" name="specialty" placeholder="e.g., Computer Science, Engineering...">
                    </div>

                    <div id="bookingTypeSection" class="form-group" style="display: none;">
                        <label><i class="fas fa-bed"></i> <span language-switch="booking_type_label">Booking Type:</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="booking_type" value="full_room" checked>
                                <span language-switch="full_room_option">Book Entire Room</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="booking_type" value="shared_spot">
                                <span language-switch="shared_spot_option">Book One Spot (Looking for Roommate)</span>
                            </label>
                        </div>
                    </div>

                    <div id="roommateSeekersSection" class="form-group" style="display: none;">
                        <label><i class="fas fa-users"></i> <span language-switch="roommate_seekers_label">Students Looking for Roommates:</span></label>
                        <div id="roommatesList" class="roommates-list">
                            <div class="loading-roommates">
                                <i class="fas fa-spinner fa-spin"></i> <span language-switch="loading_roommates">Loading...</span>
                            </div>
                        </div>
                        <input type="hidden" name="selected_roommate_id" id="selectedRoommateId">
                        <p class="info-text">
                            <i class="fas fa-info-circle"></i>
                            <span language-switch="roommate_info">Select a student to share the room with, or leave empty to be added to the waiting list.</span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> <span language-switch="phone">Phone Number:</span></label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="start_date"><i class="fas fa-calendar"></i> <span language-switch="start_date_label">Start Date:</span></label>
                        <input type="text" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="booking_duration_months"><i class="fas fa-clock"></i> <span language-switch="duration_months">Duration (Months):</span></label>
                        <input type="number" id="booking_duration_months" name="booking_duration_months" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method"><i class="fas fa-credit-card"></i> <span language-switch="payment_method_label">Payment Method:</span></label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="" language-switch="payment_method_label">Select Payment Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn" disabled>
                        <i class="fas fa-check"></i> <span language-switch="confirm_booking">Confirm Booking</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <button id="languageToggle" class="language-toggle" aria-label="Toggle language"></button>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        let buildingData = null;
        let selectedRoom = null;
        let currentFloor = null;

        const urlParams = new URLSearchParams(window.location.search);
        const dormId = urlParams.get('dorm_id');
        const roomType = urlParams.get('room_type');

        document.addEventListener('DOMContentLoaded', () => {
            if (!dormId || !roomType) {
                alert('Error: Missing dorm ID or room type');
                window.location.href = 'home_page.php';
                return;
            }

            document.getElementById('dormId').value = dormId;
            document.getElementById('roomType').value = roomType;

            flatpickr("#start_date", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });

            loadBuildingData();
        });        
                document.addEventListener('DOMContentLoaded', () => {
                    const btn = document.getElementById('languageToggle');
                    if (!btn) return;
        
                    btn.addEventListener('click', () => {
                        const curr = window.getLanguage ? window.getLanguage() : (localStorage.getItem('language') || 'en');
                        const next = (curr === 'ar') ? 'en' : 'ar';
                        if (typeof window.setLanguage === 'function') {
                            window.setLanguage(next);
                            if (window.buildingData && window.buildingData.floors) {
                                renderFloorSelector(window.buildingData.floors);
                                if (window.currentFloor) selectFloor(window.currentFloor);
                            }
                            if (typeof window.applyTranslations === 'function') window.applyTranslations();
                        } else {
                            localStorage.setItem('language', next);
                            location.reload();
                        }
                    });
                });
        async function loadBuildingData() {
            try {
                const response = await fetch(`get_rooms.php?dorm_id=${dormId}&room_type=${encodeURIComponent(roomType)}`);
                const data = await response.json();

                if (data.success) {
                    buildingData = data;
                    renderFloorSelector(data.floors);

                    if (data.floors.length > 0) {
                        selectFloor(data.floors[0]);
                    }
                } else {
                    throw new Error(data.error);
                }
            } catch (error) {
                console.error('Error loading building data:', error);
                document.getElementById('floorSelector').innerHTML = `
                    <div class="loading">
                        <i class="fas fa-exclamation-triangle" style="color: var(--danger-color);"></i>
                        <p>Error loading building data. Please try again.</p>
                    </div>
                `;
            }
        }

        function _t(key, fallback) {
            if (typeof window.t === 'function') {
                const val = window.t(key);
                if (val === key && typeof fallback !== 'undefined') return fallback;
                return val;
            }
            return (typeof fallback !== 'undefined') ? fallback : key;
        }

        function renderFloorSelector(floors) {
            const container = document.getElementById('floorSelector');
            container.innerHTML = '';

            const seen = new Set();

            floors.forEach(floor => {
                if (!floor || !floor.floor_id) return;
                if (seen.has(floor.floor_id)) return;
                seen.add(floor.floor_id);

                if (document.getElementById(`floor-btn-${floor.floor_id}`)) return;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'floor-btn';
                btn.id = `floor-btn-${floor.floor_id}`;
                btn.innerHTML = `
                    <div>${_t('floor', 'Floor')} ${floor.floor_number}</div>
                    <span class="floor-info">${floor.available_rooms}/${floor.rooms.length} ${_t('available', 'available')}</span>
                `;
                btn.onclick = () => selectFloor(floor);
                container.appendChild(btn);
            });
        }

        function selectFloor(floor) {
            currentFloor = floor;

            document.querySelectorAll('.floor-btn').forEach((btn) => {
                btn.classList.remove('active');
            });

            const buttons = document.querySelectorAll('.floor-btn');
            const floorIndex = buildingData.floors.findIndex(f => f.floor_id === floor.floor_id);
            if (floorIndex >= 0 && buttons[floorIndex]) {
                buttons[floorIndex].classList.add('active');
            }

            renderRooms(floor.rooms);
        }

        function renderRooms(rooms) {
            const container = document.getElementById('roomsGrid');
            container.innerHTML = '';

            if (!rooms || rooms.length === 0) {
                container.innerHTML = `<p style="text-align: center; padding: 20px; color: #999;">${_t('no_rooms_available', 'No rooms available on this floor')}</p>`;
                return;
            }

            rooms.forEach(room => {
                const card = document.createElement('div');
                const isAvailable = room.is_available && room.available_spots > 0;
                const isSelected = selectedRoom && selectedRoom.room_id === room.room_id;

                card.className = `room-card ${isAvailable ? 'available' : 'occupied'} ${isSelected ? 'selected' : ''}`;

                if (isAvailable) {
                    card.addEventListener('click', (e) => selectRoom(room, e));
                }

                card.innerHTML = `
                    <div class="room-status ${isAvailable ? 'available' : 'occupied'}"></div>
                    <div class="room-number">${room.room_number}</div>
                    <div class="room-type">${room.room_type}</div>
                    <div class="room-price">${room.price_per_month} JOD</div>
                `;

                container.appendChild(card);
            });
        }

        function selectRoom(room, evt) {
            selectedRoom = room;

            document.querySelectorAll('.room-card').forEach(card => {
                card.classList.remove('selected');
            });
            if (evt && evt.currentTarget) evt.currentTarget.classList.add('selected');

            document.getElementById('selectedRoomInfo').style.display = 'block';
            document.getElementById('infoRoomNumber').textContent = room.room_number;
            document.getElementById('infoRoomType').textContent = room.room_type;
            document.getElementById('infoPosition').textContent = room.position || 'N/A';
            document.getElementById('infoView').textContent = room.view_type || 'N/A';
            document.getElementById('infoPrice').textContent = room.price_per_month;

            document.getElementById('roomId').value = room.room_id;
            document.getElementById('price').value = room.price_per_month;

            const bookingTypeSection = document.getElementById('bookingTypeSection');
            if (room.room_type === 'Double' || room.room_type === 'Triple') {
                bookingTypeSection.style.display = 'block';
            } else {
                bookingTypeSection.style.display = 'none';
                document.querySelector('input[name="booking_type"][value="full_room"]').checked = true;
                document.getElementById('roommateSeekersSection').style.display = 'none';
            }

            document.getElementById('submitBtn').disabled = false;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const bookingTypeRadios = document.querySelectorAll('input[name="booking_type"]');
            bookingTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const roommateSection = document.getElementById('roommateSeekersSection');
                    if (this.value === 'shared_spot') {
                        roommateSection.style.display = 'block';
                        loadRoommateSeekers();
                    } else {
                        roommateSection.style.display = 'none';
                        document.getElementById('selectedRoommateId').value = '';
                    }
                });
            });
        });

        async function loadRoommateSeekers() {
            const roommatesList = document.getElementById('roommatesList');
            const gender = document.getElementById('gender').value;

            if (!gender) {
                roommatesList.innerHTML = `
                    <div class="info-text">
                        <i class="fas fa-info-circle"></i>
                        <span>Please select your gender first to see matching roommates.</span>
                    </div>
                `;
                return;
            }

            roommatesList.innerHTML = `
                <div class="loading-roommates">
                    <i class="fas fa-spinner fa-spin"></i> Loading roommate seekers...
                </div>
            `;

            try {
                const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;

                const response = await fetch(
                    `get_roommate_seekers.php?dorm_id=${dormId}&room_type=${encodeURIComponent(roomType)}&gender=${gender}&current_user_id=${currentUserId}`
                );
                const data = await response.json();

                if (data.success && data.seekers.length > 0) {
                    renderRoommateSeekers(data.seekers);
                } else {
                    roommatesList.innerHTML = `
                        <div class="no-roommates">
                            <i class="fas fa-users-slash"></i>
                            <p>No students currently looking for roommates in this room type.</p>
                            <p style="font-size: 0.9em; margin-top: 10px;">You can still book and be added to the waiting list.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading roommate seekers:', error);
                roommatesList.innerHTML = `
                    <div class="no-roommates">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading roommate seekers. Please try again.</p>
                    </div>
                `;
            }
        }

        function renderRoommateSeekers(seekers) {
            const roommatesList = document.getElementById('roommatesList');
            roommatesList.innerHTML = '';

            seekers.forEach(seeker => {
                const card = document.createElement('div');
                card.className = 'roommate-card';
                card.dataset.requestId = seeker.request_id;
                card.dataset.studentId = seeker.student_id;

                card.innerHTML = `
                    <div class="roommate-info">
                        <div class="roommate-specialty">
                            <i class="fas fa-graduation-cap"></i> ${seeker.specialty}
                        </div>
                        <div class="roommate-meta">
                            <i class="fas fa-bed"></i> ${seeker.room_type} Room
                            <span style="margin: 0 8px;">•</span>
                            <i class="fas fa-clock"></i> ${seeker.days_ago} days ago
                        </div>
                    </div>
                    <div class="roommate-select-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                `;

                card.addEventListener('click', () => selectRoommate(card, seeker));
                roommatesList.appendChild(card);
            });
        }

        function selectRoommate(cardElement, seeker) {
            document.querySelectorAll('.roommate-card').forEach(card => {
                card.classList.remove('selected');
            });

            cardElement.classList.add('selected');
            document.getElementById('selectedRoommateId').value = seeker.student_id;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const genderSelect = document.getElementById('gender');
            genderSelect.addEventListener('change', () => {
                const bookingType = document.querySelector('input[name="booking_type"]:checked');
                if (bookingType && bookingType.value === 'shared_spot') {
                    loadRoommateSeekers();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('languageToggle');
            if (!btn) return;

            btn.addEventListener('click', () => {
                const curr = window.getLanguage ? window.getLanguage() : (localStorage.getItem('language') || 'en');
                const next = (curr === 'ar') ? 'en' : 'ar';
                if (typeof window.setLanguage === 'function') {
                    window.setLanguage(next);
                } else {
                    localStorage.setItem('language', next);
                    location.reload();
                }
            });

            document.addEventListener('languageChanged', (e) => {
                
                if (window.buildingData && window.buildingData.floors) {
                    renderFloorSelector(window.buildingData.floors);
                    if (window.currentFloor) selectFloor(window.currentFloor);
                }

                const newLang = e?.detail?.lang || (typeof window.getLanguage === 'function' ? window.getLanguage() : localStorage.getItem('language'));
                btn.textContent = (newLang === 'ar') ? 'English' : 'العربية';
            });
        });

        document.getElementById('bookingForm').addEventListener('submit', (e) => {
            if (!selectedRoom) {
                e.preventDefault();
                const msg = (window.currentLang === 'ar') ? 'يرجى اختيار غرفة أولاً' : 'Please select a room first';
        alert(msg);
        return false;
            }
        });
    </script>

</body>
</html>