<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About MU-DORMS</title>
  <link rel="stylesheet" href="rtl-support.css">
  <style>
    :root {
      --primary-color: #007bff;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #fafafa;
      color: #333;
    }

    header {
      background: #fff;
      padding: 1rem 2rem;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      margin: 0;
      font-size: 20px;
    }

    nav {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    main {
      padding: 2rem;
      max-width: 900px;
      margin: auto;
    }

    h2 {
      margin-top: 2rem;
      color: #111;
    }

    p {
      line-height: 1.6;
    }

    .values-list {
      margin-top: 1rem;
      padding-left: 1.5rem;
    }

    .values-list li {
      margin-bottom: 0.5rem;
    }

    .team-section {
      margin-top: 3rem;
      text-align: center;
    }

    .team-members {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }

    .team-member {
      text-align: center;
      max-width: 150px;
    }

    .team-member img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      background: #f0f0f0;
    }

    .team-member p {
      margin-top: 0.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!--  اللغة -->
  <button id="languageToggle">العربية</button>

  <header>
    <h1>MU-DORMS</h1>
    <nav>
      <a href="home_page.php" data-translate="home">Home</a>
      <a href="contact.php" data-translate="Contact">Contact</a>
      <img src="https://png.pngtree.com/png-vector/20220817/ourmid/pngtree-women-cartoon-avatar-in-flat-style-png-image_6110776.png" alt="Profile" style="width:32px;height:32px;border-radius:50%;">
    </nav>
  </header>

  <main>
    <h2 data-translate="about_title">About MU-DORMS</h2>

    <h3 data-translate="our_history">Our History</h3>
    <p data-translate="history_text">
      MU-DORMS was established in 2024 with the goal of providing a supportive and enriching living environment for students. Over the years, we have grown to accommodate thousands of students, fostering a sense of community and belonging. Our commitment to student success remains at the heart of everything we do.
    </p>

    <h3 data-translate="our_mission">Our Mission</h3>
    <p data-translate="mission_text">
      Our mission is to create a vibrant and inclusive residential community that supports the academic, personal, and social development of our students. We strive to offer safe, comfortable, and engaging living spaces that enhance the overall college experience.
    </p>

    <h3 data-translate="our_values">Our Values</h3>
    <ul class="values-list">
      <li><strong data-translate="value_community">Community:</strong> <span data-translate="value_community_text">Fostering a sense of belonging and mutual respect among residents.</span></li>
      <li><strong data-translate="value_inclusivity">Inclusivity:</strong> <span data-translate="value_inclusivity_text">Embracing diversity and creating an environment where all students feel welcome and valued.</span></li>
      <li><strong data-translate="value_support">Support:</strong> <span data-translate="value_support_text">Providing resources and assistance to help students succeed academically and personally.</span></li>
      <li><strong data-translate="value_growth">Growth:</strong> <span data-translate="value_growth_text">Encouraging personal development and leadership opportunities.</span></li>
      <li><strong data-translate="value_safety">Safety:</strong> <span data-translate="value_safety_text">Ensuring a secure and healthy living environment for all residents.</span></li>
    </ul>

    <h3 data-translate="admin_message">Message from the Administration</h3>
    <p data-translate="admin_message_text">
      Welcome to MU-DORMS! We are thrilled to have you as part of our community. Our dedicated team is committed to providing you with an exceptional living experience. We encourage you to get involved, make connections, and take advantage of all the resources available to you. We are here to support you every step of the way.
    </p>

    <div class="team-section">
      <h3 data-translate="our_team">Our Team</h3>
      <div class="team-members">
        <div class="team-member">
          <img src="data:image/jpeg;base64,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" alt="Reema Al-Sawaeer">
          <p>Reema Al-Sawaeer</p>
        </div>
        <div class="team-member">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTti4pWA2v_04dwZEtPFmg1ciGQY1N1fT2ZXn_zfbgbjzy7t53t9wsv_WG1XkLdgQ2fkDo&usqp=CAU" alt="Esraa Omar">
          <p>Esraa Omar</p>
        </div>
        <div class="team-member">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxgFNE5C5Yhsc-5zZendSJOfMopXtT7vB9xuwych2-USx_tjLBuTKl-dApRIHYVvjMKAo&usqp=CAU" alt="Nebal Al-Qaralleh">
          <p>Nebal Al-Qaralleh</p>
        </div>
      </div>
    </div>
  </main>
  <script src="language-switcher.js"></script>
</body>
</html>
