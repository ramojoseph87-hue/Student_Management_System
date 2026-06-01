<?php
session_start();
include 'config.php';

// ✅ SIGURADONG NAKA LOGIN AT TEACHER LANG
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Teacher'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// ✅ MGA NUMERO LANG, WALANG HINAHANAP NA IBANG TABLE
$subj_count = 4;
$stud_count = 125;
$class_count = 5;
$pending_grades = 12;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | SAMS</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="icon" href="Untitled.png" type="image/x-icon">
    <style>
        .sidebar {
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
            justify-content: space-between !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            overflow: hidden !important;
        }
        .nav-links li {
            margin: 2px 0 !important;
        }
        .nav-links li a {
            padding: 8px 15px !important;
            font-size: 14px !important;
        }
        .sidebar-header {
            padding: 10px 5px !important;
            margin-bottom: 5px !important;
        }
        .sidebar-header img {
            width: 70px !important;
            height: auto !important;
        }
        .logout-btn {
            margin-top: auto !important;
            padding: 8px 15px !important;
            flex-shrink: 0 !important;
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

<div class="sidebar">
    <div class="sidebar-header">
        <img src="Untitled.png" alt="STAC Logo">
        <h2>SAMS</h2>
        <p>Faculty Portal</p>
    </div>
    
    <ul class="nav-links">
        <li><a href="Dashboard_Teacher.php" class="active">🏠 Dashboard</a></li>
        <li><a href="#">👤 My Profile</a></li>
        <li><a href="#">🏫 My Classes</a></li>
        <li><a href="#">👥 Student List</a></li>
        <li><a href="#">📝 Grading Sheet</a></li>
        <li><a href="#">📅 Teaching Schedule</a></li>
        <li><a href="#">📢 Post Announcement</a></li>
        <li><a href="#">📊 Quiz & Exam</a></li>
    </ul>
    
    <div class="logout-btn">
        <a href="#" onclick="if(confirm('⚠️ Are you sure you want to log out?')) { window.location.href='logout.php'; } return false;">🚪 Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="welcome-card">
        <h1>Welcome, <?php echo ucwords($user['firstname'] . ' ' . $user['lastname']); ?>!</h1>
        <p>Department: <?php echo !empty($user['department']) ? $user['department'] : 'Not Assigned'; ?> | Subject: <?php echo !empty($user['subject_handled']) ? $user['subject_handled'] : 'Not Assigned'; ?></p>
    </div>

    <div class="cards-container">
        <div class="card">
            <h3>Subjects Handled</h3>
            <div class="number"><?php echo $subj_count; ?></div>
            <p>Different Subjects</p>
        </div>

        <div class="card">
            <h3>Total Students</h3>
            <div class="number"><?php echo $stud_count; ?></div>
            <p>Under your advisory</p>
        </div>

        <div class="card">
            <h3>Classes / Sections</h3>
            <div class="number"><?php echo $class_count; ?></div>
            <p>Active Classes</p>
        </div>

        <div class="card">
            <h3>Pending Grades</h3>
            <div class="number"><?php echo $pending_grades; ?></div>
            <p>Need to submit</p>
        </div>
    </div>
</div>

<script>
const toggle = document.getElementById('darkmode');
if(localStorage.getItem('darkMode') === 'enabled') {
    document.documentElement.classList.add('dark-mode');
    toggle.checked = true;
}
toggle.addEventListener('change', () => {
    toggle.checked ? document.documentElement.classList.add('dark-mode') : document.documentElement.classList.remove('dark-mode');
    localStorage.setItem('darkMode', toggle.checked ? 'enabled' : 'disabled');
});
</script>

</body>
</html>