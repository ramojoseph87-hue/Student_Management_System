<?php
session_start();
// ✅ TINANGGAL KO MUNA ANG CONFIG HABANG HINDI PA NAKA-BUKAS ANG MYSQL
// include "../STUDENTS/config.php"; 

// ✅ DETALYE NG GURO - nandito na muna mismo
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

// ✅ HALAGA NG DATA - nilagay ko na lang dito para may lumabas na numero
$total_subjects = 4;
$total_students = 165;
$pending_grades = 8;

// ✅ LISTAHAN NG KLASE
$my_classes = [
    ['code' => 'CS 101', 'name' => 'Introduction to Computing', 'sec' => '1-A', 'studs' => 42, 'sched' => 'Mon/Thu 8:00 - 9:30 AM'],
    ['code' => 'CS 202', 'name' => 'Programming 1', 'sec' => '2-B', 'studs' => 38, 'sched' => 'Tue/Fri 10:00 - 11:30 AM'],
    ['code' => 'IT 105', 'name' => 'Computer Networks', 'sec' => '1-C', 'studs' => 40, 'sched' => 'Wed 1:00 - 3:00 PM'],
    ['code' => 'PE 101', 'name' => 'Physical Education', 'sec' => '1-D', 'studs' => 45, 'sched' => 'Mon 2:00 - 4:00 PM'],
];

$notifs = [
    ['msg' => 'Deadline of Grades: June 15, 2026', 'time' => '3 hours ago'],
    ['msg' => 'New Announcement Posted', 'time' => 'Yesterday'],
    ['msg' => 'No classes on June 12 (Holiday)', 'time' => '2 days ago']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | SAMS - STAC</title>

    <!-- ✅ KONEKTA SA CSS MO: style.css -->
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
</head>
<body>
    <div class="app-container">

        <!-- ✅ SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="STAC Logo">
                <h2>SAMS | TEACHER</h2>
                <p>Faculty Portal</p>
                <hr>
                <p class="teacher-info">👨‍🏫 <?= $_SESSION['teacher_name'] ?></p>
                <p style="font-size: 11px; opacity: 0.7; margin-top: 4px;"><?= $_SESSION['department'] ?></p>
            </div>

            <ul class="nav-links">
                <li><a href="teacher_dashboard.php" class="active">🏠 Dashboard</a></li>
                <li><a href="teacher_classes.php">🏫 My Classes</a></li>
                <li><a href="teacher_students.php">👨‍🎓 Students</a></li>
                <li><a href="teacher_grades.php">📝 Grades Management</a></li>
                <li><a href="teacher_attendance.php">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
            </ul>

            <div class="logout-btn">
              <a href="../STUDENTS/logout.php" onclick="confirmLogout(); return false;">🚪 Logout</a>
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

            <!-- ✅ WELCOME HEADER -->
            <div class="welcome-card">
                <h1>Welcome Back, Teacher!</h1>
                <div class="info-row">
                    <span>Sogod Southern Leyte | STAC</span>
                    <span class="badge">A.Y. 2025-2026</span>
                    <span class="badge">1st Semester</span>
                </div>
            </div>

            <!-- ✅ STATS / BILANGAN -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3>Subjects Handled</h3>
                    <div class="num blue"><?= $total_subjects ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <div class="num green"><?= $total_students ?></div>
                </div>
                <div class="stat-card">
                    <h3>Pending Grades</h3>
                    <div class="num red"><?= $pending_grades ?></div>
                </div>
                <div class="stat-card">
                    <h3>Announcements</h3>
                    <div class="num orange"><?= count($notifs) ?></div>
                </div>
            </div>

            <!-- ✅ MY CLASSES -->
            <div class="card">
                <h2>🏫 My Classes & Subjects</h2>
                <?php foreach($my_classes as $cls): ?>
                <div class="item-row">
                    <div>
                        <strong><?= $cls['code'] ?> - <?= $cls['name'] ?></strong>
                        <div style="font-size: 13px; opacity: 0.7; margin-top: 4px;">
                            Section <?= $cls['sec'] ?> | <?= $cls['studs'] ?> Students | 🕒 <?= $cls['sched'] ?>
                        </div>
                    </div>
                    <a href="teacher_view_class.php?code=<?= $cls['code'] ?>" class="action-btn btn-primary">View</a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ✅ RECENT UPDATES / NOTIFICATIONS -->
            <div class="card">
                <h2>🔔 Recent Updates</h2>
                <?php foreach($notifs as $note): ?>
                <div class="item-row">
                    <div><?= $note['msg'] ?></div>
                    <div style="font-size: 12px; opacity: 0.6;"><?= $note['time'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

    <!-- ✅ KONEKTA SA JS MO: teacher.js -->
    <script src="teacher.js"></script>
</body>
</html>