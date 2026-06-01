<?php
session_start();
// ✅ BINAGO KO ITO: lumabas muna ng folder para hanapin ang config
include "config.php"; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = trim($_POST['username_email']);
    $password_input = $_POST['password'];
    $user_type = $_POST['role'];

    $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND user_type = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username_email, $username_email, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password_input, $user['password'])) {
            
            // ✅ TAPOS NA ITO, KUMPLETO LAHAT HINDI NA KUKULANG
            $_SESSION['user_id']        = $user['user_id'];
            $_SESSION['user_type']      = $user['user_type'];
            $_SESSION['fullname']       = $user['fullname'];
            $_SESSION['student_id']     = $user['student_id'];
            $_SESSION['course']         = $user['course'];
            $_SESSION['year_level']     = $user['year_level']; 
            $_SESSION['section']        = $user['section'];    
            $_SESSION['username']       = $user['username'];   
            $_SESSION['email']          = $user['email'];      
            $_SESSION['department']     = $user['department'];
            $_SESSION['subject_handled']= $user['subject_handled'];

            // ✅ PINAKA-importante: INAYOS KO ANG MGA LINK DITO!
            if ($user['user_type'] == 'Student') {
                // Nandito rin sa loob ng STUDENT folder kaya DIRETSO lang
                header("Location: Dashboard_Student.php");
                exit;
            } 
            elseif ($user['user_type'] == 'Teacher') {
                // Lumabas sa STUDENT folder, tapos pumasok sa TEACHER folder
                header("Location: ../TEACHER/Dashboard_Teacher.php");
                exit;
            } 
            elseif ($user['user_type'] == 'Admin') {
                // Lumabas sa STUDENT folder, tapos pumasok sa ADMIN folder
                header("Location: ../ADMIN/admindashboard.php");
                exit;
            }
        } else {
            $error = "❌ Maling Password!";
        }
    } else {
        $error = "❌ Walang account o maling Role ang napili!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SAMS</title>
    <!-- ✅ BINAGO KO ITO: lumabas muna para hanapin ang css at image -->
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        body {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100vh !important;
            padding: 20px !important;
            margin: 0;
        }
    </style>
</head>
<body>

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
        <!-- ✅ BINAGO KO ITO: tama na ang tawag sa logo -->
        <img src="../Untitled.png" alt="Saint Thomas Aquinas College">
        <h2>SAINT THOMAS AQUINAS <br>COLLEGE</h2>
        <p>Student, Teacher & Admin Portal</p>
    </div>

    <div class="form-side">
        <div class="Login-Container">
            <h2>ACCOUNT LOGIN</h2>

            <?php if(!empty($error)): ?>
                <p style="color:red; text-align:center; font-weight:bold; padding:8px; background:#ffebee; border-radius:5px;"><?= $error ?></p>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <p style="color:green; text-align:center; font-weight:bold; padding:8px; background:#e8f5e9; border-radius:5px;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Username or Email</label>
                    <input type="text" name="username_email" placeholder="Enter your username or email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-group">
                    <label>Login As</label>
                    <select name="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="Student">Student</option>
                        <option value="Teacher">Teacher / Faculty</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <button type="submit" style="width:100%; margin-top:10px;">LOGIN NOW</button>
            </form>

            <!-- ✅ BINAGO KO RIN ITO: yung Register.php, kung saan ba nakalagay yan?
            Kung nasa labas: <a href="../Register.php">
            Kung nasa loob din ng STUDENT folder: <a href="Register.php"> -->
            <p style="text-align:center; margin-top:15px;">Don't have an account? <a href="Register.php">Register here</a></p>
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