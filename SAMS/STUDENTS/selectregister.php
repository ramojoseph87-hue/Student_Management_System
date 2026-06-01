<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Registration | SAMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Untitled.png" type="image/x-icon">
    <style>
        /* ✅ PAGE SETUP */
        body {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100vh !important;
            padding: 20px !important;
            margin: 0;
        }

        .main-wrapper {
            display: flex;
            width: 850px;
            height: 620px;
            max-width: 100%;
            background-color: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(2, 136, 209, 0.15);
            border: 1px solid rgba(3, 169, 244, 0.35);
            overflow: hidden;
            position: relative;
        }

        .school-side {
            width: 45%;
            background-color: rgba(1, 87, 155, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            text-align: center;
            border-right: 1px solid rgba(3, 169, 244, 0.2);
        }

        .school-side img {
            max-width: 110px;
            height: auto;
            margin-bottom: 12px;
        }

        .school-side h2 {
            color: #01579B;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .school-side p {
            color: #0288D1;
            font-size: 14px;
            font-weight: 500;
            /* ✅ WALA NA YUNG MGA KURSO DITO */
        }

        .form-side {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        /* ✅ DITO NAKA-CENTER LAHAT SA KANANG BAHAGI */
        .selection-container {
            display: flex;
            flex-direction: column;
            gap: 22px;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .card {
            width: 190px;
            padding: 25px 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(2,136,209,0.15);
            text-align: center;
            cursor: pointer;
            border: 1px solid rgba(3,169,244,0.25);
        }

        .card h3 {
            color: #01579B;
            font-size: 16px;
            margin: 0 0 5px 0;
        }

        .card p {
            color: #0288D1;
            font-size: 13px;
            margin: 0;
        }

        /* ✅ ETO NA!!! NASA KANAN AT NASA ILALIM NG ADMIN */
        .login-link {
            width: 190px;
            text-align: center;
            margin-top: 5px;
            font-size: 14px;
            color: #888888;
        }

        .login-link a {
            display: block;
            margin-top: 3px;
            color: #0288D1;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* ✅ DARK MODE */
        body.dark-mode .main-wrapper {
            background-color: rgba(10, 36, 99, 0.55);
            border-color: rgba(100, 180, 246, 0.4);
        }

        body.dark-mode .school-side {
            background-color: rgba(24, 45, 80, 0.2);
            border-color: rgba(100, 180, 246, 0.2);
        }

        body.dark-mode .school-side h2 { color: #90CAF9; }
        body.dark-mode .school-side p { color: #81D4FA; }

        body.dark-mode .card {
            background: rgba(40, 60, 90, 0.4);
            border-color: rgba(100,180,246,0.3);
        }

        body.dark-mode .card h3 { color: #90CAF9; }
        body.dark-mode .card p { color: #81D4FA; }

        body.dark-mode .login-link { color: #cccccc; }
        body.dark-mode .login-link a { color: #81D4FA; }
    </style>
</head>
<body>

    <!-- 🔘 DARK MODE SWITCH -->
    <div class="mode-switch">
        <span>☀️</span>
        <label class="switch">
            <input type="checkbox" id="darkmode">
            <span class="slider"></span>
        </label>
        <span>🌙</span>
    </div>

    <div class="main-wrapper">
        <div class="school-side">
            <img src="Untitled.png" alt="School Logo">
            <h2>SAINT THOMAS AQUINAS <br>COLLEGE</h2>
            <p>Student, Teacher & Admin Portal</p>
        </div>

        <div class="form-side">
            <div class="selection-container">

                <!-- STUDENT -->
                <div class="card" onclick="window.location.href='Register.php'">
                    <h3>🎓 Student</h3>
                    <p>Register here</p>
                </div>

                <!-- TEACHER -->
                <div class="card" onclick="window.location.href='Register2.php'">
                    <h3>👨‍🏫 Teacher / Faculty</h3>
                    <p>Register here</p>
                </div>

                <!-- ADMIN -->
                <div class="card" onclick="window.location.href='Registeradmin.php'">
                    <h3>🔐 Admin</h3>
                    <p>Register here</p>
                </div>

                <!-- ✅ DITO NA! NASA KANAN AT NASA ILALIM NG ADMIN -->
                <div class="login-link">
                    Do you have an account?
                    <a href="login.php">Proceed to Login</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('darkmode');
        if(localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            toggle.checked = true;
        }
        toggle.addEventListener('change', () => {
            if(toggle.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    </script>

</body>
</html>