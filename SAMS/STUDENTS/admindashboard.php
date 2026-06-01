<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// bilangin lahat
$total_students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_type = 'Student'"));
$total_teachers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_type = 'Teacher'"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SAMS</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="icon" href="untitled.png" type="image/x-icon">
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
            text-align: center;
        }
        .sidebar-header img {
            width: 80px !important;
            height: auto !important;
            margin-bottom: 8px;
        }
        .logout-btn {
            margin-top: auto !important;
            padding: 8px 15px !important;
            flex-shrink: 0 !important;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .dark-mode .stat-card {
            background: #1E293B;
            color: #E0E7FF;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #01579B;
            font-size: 16px;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #0288D1;
        }
        .stat-card p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }
        .dark-mode .stat-card p {
            color: #B0BEC5;
        }
        .status-active {
            color: green;
            font-weight: bold;
            font-size: 24px;
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
            <img src="Untitled.png" alt="School Logo">
            <h2>SAMS</h2>
            <p>Saint Thomas Aquinas College</p>
            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 8px 0;">
            <!-- ✅ DIRETSO NA: Kung ano ang FULLNAME niya, 'yun lang lalabas -->
            <p style="font-size: 13px; opacity: 0.9;">Welcome, <?php echo $user['fullname']; ?>!</p>
        </div>

        <ul class="nav-links">
            <li><a href="admindashboard.php" class="active">🏠 Dashboard</a></li>
            <li><a href="#">👥 Manage Students</a></li>
            <li><a href="#">👨‍🏫 Manage Teachers</a></li>
            <li><a href="#">📚 Manage Subjects</a></li>
            <li><a href="#">🗓️ Manage Schedules</a></li>
            <li><a href="#">📊 View All Grades</a></li>
            <li><a href="Profile.php">👤 My Profile</a></li>
        </ul>

        <div class="logout-btn">
            <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <!-- ✅ GANITO RIN: DIRETSO LANG -->
            <h1>Welcome, <?php echo $user['fullname']; ?>!</h1>
            <p>System Administrator | Saint Thomas Aquinas College</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Students</h3>
                <div class="number"><?php echo $total_students; ?></div>
                <p>Registered Students</p>
            </div>
            <div class="stat-card">
                <h3>Total Teachers</h3>
                <div class="number"><?php echo $total_teachers; ?></div>
                <p>Registered Teachers</p>
            </div>
            <div class="stat-card">
                <h3>System Users</h3>
                <div class="number"><?php echo $total_users; ?></div>
                <p>All Accounts</p>
            </div>
            <div class="stat-card">
                <h3>System Status</h3>
                <div class="status-active">Active</div>
                <p>Running Smoothly</p>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>