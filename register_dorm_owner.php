<?php include 'conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Registration | MU-DORMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #346adf;
            --primary-hover: #22489a;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --border: #e2e8f0;
        }

        /* إخفاء أسهم الزيادة والنقصان في حقول الأرقام */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .reg-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 40px 20px;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .reg-header {
            background: linear-gradient(135deg, #1e6bca 0%, #3a6ded 100%);
            padding: 40px;
            color: white;
            text-align: center;
        }

        .reg-header h2 { margin: 0; font-size: 2rem; font-weight: 700; }
        .reg-header p { margin: 10px 0 0; opacity: 0.9; font-weight: 300; }

        form { padding: 40px; }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            margin-top: 20px;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .input-box { display: flex; flex-direction: column; gap: 8px; }
        .full-width { grid-column: span 2; }

        label { font-size: 0.85rem; font-weight: 600; color: var(--text-main); }

        input, select, textarea {
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
            background: #fff;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .amenity-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }

        .amenity-chip input { display: none; }

        .amenity-chip:has(input:checked) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .submit-area {
            margin-top: 40px;
            text-align: center;
        }

        .btn-grad {
            background: linear-gradient(135deg, #307dea 0%, #3a64ed 100%);
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
            width: 100%;
        }

        .btn-grad:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(79, 70, 229, 0.3);
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>

<div class="reg-wrapper">
    <div class="reg-header">
        <h2><i class="fas fa-key"></i> Join MU-DORMS</h2>
        <p>Start managing your property and reaching thousands of students</p>
    </div>

    <form action="owner_reg_process.php" method="POST">
        
        <div class="section-title"><i class="fas fa-user-circle"></i> Account Information</div>
        <div class="form-grid">
            <div class="input-box">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="E.g. Ahmed Ali" required>
            </div>
            <div class="input-box">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="owner@example.com" required>
            </div>
            <div class="input-box">
                <label>Create Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" required>
            </div>
            <div class="input-box">
                <label>Phone Number (10 Digits)</label>
                <input type="text" name="phone" placeholder="07XXXXXXXX" 
                       pattern="\d{10}" maxlength="10" minlength="10" 
                       title="Please enter exactly 10 digits" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
            </div>
        </div>

        <div class="section-title"><i class="fas fa-hotel"></i> Dormitory Details</div>
        <div class="form-grid">
            <div class="input-box">
                <label>Dorm Name (English)</label>
                <input type="text" name="dorm_name" placeholder="E.g. Al-Amal Dorm" required>
            </div>
            <div class="input-box">
                <label>اسم السكن (بالعربي)</label>
                <input type="text" name="name_ar" dir="rtl" placeholder="مثال: سكن الأمل" required>
            </div>
            <div class="input-box">
                <label>Location Area</label>
                <select name="location">
                    <option value="Mutah">Mutah (Near University)</option>
                    <option value="Mirwid">Mirwid</option>
                    <option value="Aleadnania">Aleadnania</option>
                    <option value="Zahoum">Zahoum</option>
                </select>
            </div>
            <div class="input-box">
                <label>Target Gender</label>
                <select name="gender">
                    <option value="female">Female Students</option>
                    <option value="male">Male Students</option>
                </select>
            </div>
            <div class="input-box">
                <label>Proximity to University</label>
                <input type="text" name="proximity" placeholder="E.g. 500m or 1 mile">
            </div>
            <div class="input-box">
                <label>Total Number of Rooms</label>
                <input type="number" name="number_of_rooms" min="1" placeholder="Ex: 50" 
                       oninput="validity.valid||(value='');" required>
            </div>
            <div class="input-box full-width">
                <label>Dorm Image URL</label>
                <input type="text" name="image_url" placeholder="https://link-to-image.jpg">
            </div>
            <div class="input-box full-width">
                <label>About the Dorm (Description)</label>
                <textarea name="description" rows="3" placeholder="Tell students about your facilities..."></textarea>
            </div>
        </div>

        <div class="section-title"><i class="fas fa-tags"></i> Monthly Pricing (JOD)</div>
        <div class="form-grid">
            <div class="input-box">
                <label><i class="fas fa-user"></i> Single Room Price</label>
                <input type="number" name="price_single" min="0" placeholder="Price" oninput="validity.valid||(value='');">
            </div>
            <div class="input-box">
                <label><i class="fas fa-users"></i> Double Room Price</label>
                <input type="number" name="price_double" min="0" placeholder="Price" oninput="validity.valid||(value='');">
            </div>
            <div class="input-box">
                <label><i class="fas fa-users-rays"></i> Triple Room Price</label>
                <input type="number" name="price_triple" min="0" placeholder="Price" oninput="validity.valid||(value='');">
            </div>
        </div>

        <div class="section-title"><i class="fas fa-star"></i> Available Amenities</div>
        <div class="amenities-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM amenities");
            while($row = $stmt->fetch()) {
                echo '
                <label class="amenity-chip">
                    <input type="checkbox" name="amenities[]" value="'.$row['amenity_id'].'">
                    <i class="fas fa-check-circle"></i>
                    '.$row['amenity_name'].'
                </label>';
            }
            ?>
        </div>

        <div class="submit-area">
            <button type="submit" name="register_owner" class="btn-grad">
                Submit Application <i class="fas fa-arrow-right" style="margin-left:10px;"></i>
            </button>
            <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 15px;">
                * Your application will be reviewed by the admin before activation.
            </p>
        </div>
    </form>
</div>

</body>
</html>