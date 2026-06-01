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
$student_id = $user['student_id'];

// ✅ 1. TOTAL SUBJECTS
$count_qry = mysqli_query($conn, "SELECT COUNT(*) AS total FROM student_subjects WHERE student_id = '$student_id'");
$count_row = mysqli_fetch_assoc($count_qry);
$total_subjects = $count_row['total'] ?? 0;

// ✅ 2. TOTAL UNITS
$units_qry = mysqli_query($conn, "SELECT SUM(units) AS total_units FROM student_subjects WHERE student_id = '$student_id'");
$units_row = mysqli_fetch_assoc($units_qry);
$total_units = $units_row['total_units'] ?? 0;

// ✅ 3. OUTSTANDING BALANCE
$outstanding_balance = 0;

// ✅ 4. STATUS LOGIC
if($total_subjects <= 0){
    $status = "Not Enrolled";
    $status_color = "#ef4444"; // Pula
} else {
    if($outstanding_balance > 0){
        $status = "Partial Payment";
        $status_color = "#f59e0b"; // Dilaw
    } else {
        $status = "Fully Paid / Enrolled";
        $status_color = "#10b981"; // Berde
    }
}

// ✅ DETALYE NG ESTUDYANTE
$student_type = isset($user['student_type']) ? $user['student_type'] : "Regular"; 
$current_semester = isset($user['current_semester']) ? $user['current_semester'] : "1st Semester";
$school_year = "2025-2026";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Dashboard | SAMS</title>
    <link rel="stylesheet" href="../style1.css?v=7">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
</head>
<body>

    <div class="app-container">

        <!-- ✅ SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../untitled.png" alt="School Logo">
                <h2>SAMS</h2>
                <p>Saint Thomas Aquinas College</p>
                <hr>
                <p>Welcome, 
                    <?php 
                        if(!empty($user['fullname'])){
                            echo explode(' ', $user['fullname'])[0]; 
                        } else {
                            echo $user['username'];
                        }
                    ?>!
                </p>
            </div>

            <ul class="nav-links">
                <li><a href="Dashboard_Student.php" class="active">🏠 Dashboard</a></li>
                <li><a href="Profile.php">👤 My Profile</a></li> 
                <li><a href="add_subjects.php">➕ Add Subjects</a></li>                 
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php">📑 Requirements</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="settings.php">🔐 Settings</a></li>
                <li><a href="help.php">❓ Help & Support</a></li>
            </ul>

            <div class="logout-btn">
                <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
            </div>
        </div>

        <!-- ✅ MAIN CONTENT -->
        <div class="main-content">

            <!-- ✅ DARK MODE SWITCH -->
            <div class="mode-switch">
                <span>☀️</span>
                <label class="switch">
                    <input type="checkbox" id="darkmode">
                    <span class="slider"></span>
                </label>
                <span>🌙</span>
            </div>

            <!-- ✅ WELCOME CARD -->
            <div class="welcome-card">
                <h1>Welcome back, <?php echo $_SESSION['fullname']; ?></h1>
                
                <div class="info-row">
                    <span>Academic Year <?php echo $school_year; ?></span>
                    <span class="semester-badge"><?php echo $current_semester; ?></span>
                    <span>Student Type: 
                        <span class="<?php echo ($student_type == 'Regular') ? 'type-regular' : 'type-irregular'; ?>">
                            <?php echo $student_type; ?>
                        </span>
                    </span>
                </div>
            </div>

            <!-- ✅ STATISTICS / CARDS -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3>TOTAL SUBJECTS</h3>
                    <div class="num blue"><?php echo $total_subjects; ?></div>
                </div>
                <div class="stat-card">
                    <h3>TOTAL UNITS</h3>
                    <div class="num blue"><?php echo $total_units; ?></div>
                </div>
                <div class="stat-card">
                    <h3>OUTSTANDING BALANCE</h3>
                    <div class="num <?php echo ($outstanding_balance > 0) ? 'red' : 'zero'; ?>">
                        ₱ <?php echo number_format($outstanding_balance, 2); ?>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>STATUS</h3>
                    <div class="num" style="color: <?php echo $status_color; ?>">
                        <?php echo $status; ?>
                    </div>
                </div>
            </div>

            <!-- ✅ ANNOUNCEMENTS / SCHOOL UPDATES -->
            <div class="announcement-card">
                <h2>📢 School Updates <small>• Recent Posts</small></h2>
                <div class="announcement-item">
                    <h4>Final Examination Schedule Released</h4>
                    <p>Check your schedule and be on time for your exams. Ensure you have no pending balance.</p>
                    <small>Posted: May 20, 2026 | Office of the Registrar</small>
                </div>
                <div class="announcement-item">
                    <h4>Last Day of Grade Encoding</h4>
                    <p>All grades will be available for viewing starting June 10, 2026.</p>
                    <small>Posted: May 18, 2026 | Office of the Dean</small>
                </div>
                <div class="announcement-item">
                    <h4>Early Enrollment for 2nd Semester</h4>
                    <p>Early enrollment starts June 15 to June 30, 2026. Priority for regular students.</p>
                    <small>Posted: May 15, 2026 | Accounting Office</small>
                </div>
            </div>

        </div> <!-- /Main Content -->

    </div> <!-- /App Container -->

    <script src="../student.js"></script>
</body>
</html>