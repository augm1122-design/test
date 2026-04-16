<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-DORM Project Loading</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('https://media.gettyimages.com/id/159081727/photo/buildingsides.jpg?s=612x612&w=0&k=20&c=qQxb0ZouIxgnf-XhQy6FOhOOPT_ws2w2EprslFZEz6o=');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff; 
            overflow: hidden; 
        }

        .overlay {  /*  تنسيق للطبقة الشفافة  */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        } /*  نهاية تنسيق للطبقة الشفافة  */

        .content-container {
            text-align: center;
            padding: 20px;
            display: none;
        }

        h1 {
            font-size: 3.5em; 
            color: #ffffff;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .progress-section {
            display: flex;
            align-items: center;
            justify-content: center; 
            width: 80%; 
            max-width: 600px; 
            margin-bottom: 20px;
        }

        .progress-label {
            font-size: 1.2em;
            color: #ffffff;
            white-space: nowrap;
            margin-right: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        .progress-bar-container {
            flex-grow: 1;
            height: 12px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .progress-bar {
            height: 100%;
            width: 0%; 
            background-color: #fff;
            border-radius: 6px;
            transition: width 3s ease-out; 
        }

        .progress-percentage {
            font-size: 1.2em;
            color: #ffffff;
            font-weight: bold;
            white-space: nowrap;
            margin-left: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        .instruction-message {
            font-size: 1.4em;
            color: #ffffff;
            margin-top: 30px;
            display: none;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }


        @media (max-width: 768px) {
            h1 {
                font-size: 2.8em;
                margin-bottom: 30px;
            }
            .progress-section {
                width: 90%;
            }
            .progress-label,
            .progress-percentage,
            .instruction-message {
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 2em;
                margin-bottom: 20px;
            }
            .progress-label,
            .progress-percentage,
            .instruction-message {
                font-size: 0.9em;
            }
            .progress-bar-container {
                height: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="content-container" id="contentContainer">
            <h1>MU-DORM</h1>
            <div class="progress-section">
                <span class="progress-label">Project Completion</span>
                <div class="progress-bar-container">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
                <span class="progress-percentage" id="progressPercentage">0%</span>
            </div>
            <div class="instruction-message" id="instructionMessage">
                Press ENTER or Click anywhere to continue...
            </div>
        </div>
    </div>

    <script>// لتجهيز  صفحة html 
        document.addEventListener('DOMContentLoaded', () => {
            const contentContainer = document.getElementById('contentContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const instructionMessage = document.getElementById('instructionMessage');
            let progressCompleted = false;// متغير لتتبع حالة التقدم

            
            setTimeout(() => {
                contentContainer.style.display = 'block';
                progressBar.style.width = '100%';

                let currentPercentage = 0;
                const duration = 3000; 
                const intervalTime = duration / 100; //= 30ms

                const interval = setInterval(() => { //ينشئ حلقة تكرارية لتحديث النسبة المئوية كل 30 ميلي ثانية
                    if (currentPercentage < 100) { //إذا كانت النسبة أقل من 100، يزداد المتغير  بواحد ويتم تحديث النص.
                        currentPercentage++;
                        progressPercentage.textContent = `${currentPercentage}%`;
                    } else {
                        // إذا وصلت النسبة إلى 100، تتوقف الحلقة التكرارية (clearInterval)، ويتم تحديث المتغير progressCompleted إلى true، وتظهر رسالة التعليمات.
                        clearInterval(interval);
                        progressCompleted = true;
                        instructionMessage.style.display = 'block'; 
                        progressPercentage.textContent = `100%`;
                    }
                }, intervalTime); 
            }, 500); 

           
            function goToHomePage() { //دالة تقوم بتوجيه المستخدم إلى صفحة home_page.php إذا كان التحميل قد اكتمل.
                if (progressCompleted) {
                    window.location.href = 'home_page.php';
                }
            }

            // يستمع لنقرات الماوس على أي مكان في الصفحة ويشغل الدالة goToHomePage
            document.addEventListener('click', goToHomePage);

            document.addEventListener('keydown', (event) => {// يستمع لضغطة على أي مفتاح في لوحة المفاتيح.
                //يتحقق ما إذا كان المفتاح الذي تم الضغط عليه هو مفتاح "Enter"، ثم يشغل الدالة goToHomePage.
                if (event.key === 'Enter') {
                    goToHomePage();
                }
            });
        });
    </script>
</body>
</html>