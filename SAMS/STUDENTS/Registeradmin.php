<?php
session_start();
include "config.php";

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // KUNIN LANG ANG MGA NASA DATABASE MO
    $firstname   = trim($_POST['firstname']);
    $middlename  = trim($_POST['middlename']);
    $lastname    = trim($_POST['lastname']);
    $username    = trim($_POST['username']);
    $email       = trim($_POST['email']);
    $password    = $_POST['password'];
    $confirmpass = $_POST['confirmpass'];
    $user_type   = "Teacher"; // Awtomatikong Teacher

    // CHECK KUNG TUGMA ANG PASSWORD
    if ($password !== $confirmpass) {
        $error = "❌ Hindi magkatugma ang password!";
    } else {
        // CHECK KUNG MAY GANANG USERNAME O EMAIL NA
        $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "❌ Username o Email ay ginagamit na!";
        } else {
            // I-SAVE SA DATABASE - EKSAKTO SA MERON KA
            $sql = "INSERT INTO users 
                    (firstname, middlename, lastname, username, email, password, user_type) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", 
                $firstname, 
                $middlename, 
                $lastname, 
                $username, 
                $email, 
                $password, 
                $user_type
            );

            if ($stmt->execute()) {
                $success = "✅ Teacher account created successfully! <a href='login.php'>Go to Login</a>";
            } else {
                $error = "❌ Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Teacher | SAMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Untitled.png" type="image/x-icon">
    <style>
        body {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100vh !important;
            padding: 20px !important;
            margin: 0;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            width: 100%;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        h2 {
            color: #01579B;
        }
    </style>
</head>
<body>

<!-- Dark Mode Switch -->
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
        <img src="Untitled.png" alt="Saint Thomas Aquinas College">
        <h2>SAINT THOMAS AQUINAS <br>COLLEGE</h2>
        <p>Faculty / Teacher Registration<br>BSIS | BSOA | BSBA | BEEd | BSCRIM</p>
    </div>

    <div class="form-side">
        <div class="Register-Container" style="width: 100%; max-width: 500px;">
            <h2 style="text-align:center; margin-bottom:25px;">Register as Teacher / Faculty</h2>

            <?php if($error): ?>
                <p style="color:red; text-align:center; font-weight:bold;"><?= $error ?></p>
            <?php endif; ?>

            <?php if($success): ?>
                <p style="color:green; text-align:center; font-weight:bold;"><?= $success ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="firstname" placeholder="Enter First Name" required>
                    </div>

                    <div class="form-group">
                        <label>Middle Name:</label>
                        <input type="text" name="middlename" placeholder="Optional">
                    </div>

                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" name="lastname" placeholder="Enter Last Name" required>
                    </div>

                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" placeholder="Create Username" required>
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Create Password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" name="confirmpass" placeholder="Confirm Password" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="faculty@school.edu.ph" required>
                    </div>
                </div>

                <button type="submit" style="width:100%; margin-top:20px; background:#0288D1; padding:12px; border:none; border-radius:6px; color:white; font-size:16px; font-weight:bold;">Register Faculty Account</button>
            </form>

            <p style="text-align:center; margin-top:20px;">Already have an account? <a href="login.php">Back to Login</a></p>
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
    toggle.checked ? document.body.classList.add('dark-mode') : document.body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', toggle.checked ? 'enabled' : 'disabled');
});
</script>

</body>
</html>