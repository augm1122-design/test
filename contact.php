<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا - MU-DORMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #007bff 0%, #bac8efff 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #1e3a8a;
            font-size: 2em;
        }

        .back-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #3133bfff;
            transform: translateY(-2px);
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .contact-info, .contact-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #1e3a8a;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .info-item {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-item i {
            font-size: 1.5em;
            color: #1e3a8a;
            width: 40px;
        }

        .info-item div h3 {
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .info-item div p {
            color: #1e3a8a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1e3a8a;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, #007bff 0%, #ecececff 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-envelope"></i> اتصل بنا</h1>
            <a href="home_page.php" class="back-btn">
                <i class="fas fa-home"></i> العودة للرئيسية
            </a>
        </header>

        <div class="content">
            <div class="contact-info">
                <h2>معلومات الاتصال</h2>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>العنوان</h3>
                        <p>جامعة مؤتة، الكرك، الأردن</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>الهاتف</h3>
                        <p>+962 3 237 2380</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>البريد الإلكتروني</h3>
                        <p>info@mu-dorms.com</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>ساعات العمل</h3>
                        <p>الأحد - الخميس: 8:00 صباحاً - 4:00 مساءً</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-globe"></i>
                    <div>
                        <h3>وسائل التواصل الاجتماعي</h3>
                        <p>
                            <a href="#" style="color: #667eea; margin-left: 10px;"><i class="fab fa-facebook fa-2x"></i></a>
                            <a href="#" style="color: #667eea; margin-left: 10px;"><i class="fab fa-twitter fa-2x"></i></a>
                            <a href="#" style="color: #667eea; margin-left: 10px;"><i class="fab fa-instagram fa-2x"></i></a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h2>أرسل لنا رسالة</h2>
                <form id="contactForm" onsubmit="sendMessage(event)">
                    <div class="form-group">
                        <label for="name">الاسم الكامل</label>
                        <input type="text" id="name" name="name" required placeholder="أدخل اسمك الكامل">
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" required placeholder="أدخل بريدك الإلكتروني">
                    </div>

                    <div class="form-group">
                        <label for="subject">الموضوع</label>
                        <input type="text" id="subject" name="subject" required placeholder="موضوع الرسالة">
                    </div>

                    <div class="form-group">
                        <label for="message">الرسالة</label>
                        <textarea id="message" name="message" required placeholder="اكتب رسالتك هنا..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> إرسال الرسالة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    async function sendMessage(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;

        try {
            const response = await fetch('api/send_contact_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    subject: subject,
                    message: message
                })
            });

            const data = await response.json();

            if (data.success) {
                alert('تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
                document.getElementById('contactForm').reset();
            } else {
                alert('خطأ: ' + (data.message || 'فشل إرسال الرسالة'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال الرسالة');
        }
    }
    </script>
</body>
</html>