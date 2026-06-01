<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

$attendance_list = [
    ['id' => 'STU-0045', 'name' => 'Dela Cruz, Juan A.', 'course' => 'BS Information System', 'section' => '1-A', 'days_present' => 18, 'days_absent' => 2, 'percentage' => '90%'],
    ['id' => 'STU-0122', 'name' => 'Santos, Maria B.', 'course' => 'BEEd', 'section' => '1-C', 'days_present' => 19, 'days_absent' => 1, 'percentage' => '95%'],
    ['id' => 'STU-0078', 'name' => 'Reyes, Jose C.', 'course' => 'BSCrim', 'section' => '2-B', 'days_present' => 15, 'days_absent' => 5, 'percentage' => '75%'],
    ['id' => 'STU-0091', 'name' => 'Bautista, Ana D.', 'course' => 'BSOA', 'section' => '1-D', 'days_present' => 20, 'days_absent' => 0, 'percentage' => '100%'],
    ['id' => 'STU-0104', 'name' => 'Torres, Mark E.', 'course' => 'BSBA', 'section' => '2-A', 'days_present' => 12, 'days_absent' => 8, 'percentage' => '60%'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance | SAMS - STAC</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        .attendance-card { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:20px; margin-bottom:15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .stat-box { text-align:center; padding:10px 15px; border-radius:8px; background:rgba(37, 99, 235, 0.05); }
        .present {color:var(--success); font-weight:bold;}
        .absent {color:var(--danger); font-weight:bold;}
        .warning {color:var(--warning); font-weight:bold;}
        .btn-mark {padding:6px 12px; border-radius:6px; border:none; cursor:pointer; margin:2px;}
        .btn-present {background:var(--success); color:white;}
        .btn-absent {background:var(--danger); color:white;}
        .btn-late {background:var(--warning); color:white;}
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="STAC Logo">
                <h2>SAMS | TEACHER</h2>
                <p>Faculty Portal</p>
                <hr>
                <p class="teacher-info">👨‍🏫 <?= $_SESSION['teacher_name'] ?></p>
                <p style="font-size: 11px; opacity: 0.7;"><?= $_SESSION['department'] ?></p>
            </div>
            <ul class="nav-links">
                <li><a href="teacher_dashboard.php">🏠 Dashboard</a></li>
                <li><a href="teacher_classes.php">🏫 My Classes</a></li>
                <li><a href="teacher_students.php">👨‍🎓 Students</a></li>
                <li><a href="teacher_grades.php">📝 Grades Management</a></li>
                <li><a href="teacher_attendance.php" class="active">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
            </ul>
            <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
        </div>

        <div class="main-content">
            <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

            <div class="welcome-card">
                <h1>📋 Attendance Monitoring</h1>
                <div class="info-row"><span>Record daily attendance and view summary reports</span><span class="badge">Date: <?= date('F d, Y') ?></span></div>
            </div>

            <div class="card" style="padding:15px; margin-bottom:20px;">
                <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                    <select style="padding:8px 12px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                        <option>All Courses</option>
                        <option>BS Information System</option>
                        <option>BEEd</option>
                        <option>BSCrim</option>
                        <option>BSOA</option>
                        <option>BSBA</option>
                    </select>
                    <select style="padding:8px 12px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                        <option>All Sections</option>
                        <option>1-A</option><option>1-C</option><option>1-D</option><option>2-A</option><option>2-B</option>
                    </select>
                    <button class="action-btn" style="margin-left:auto;">📅 View History</button>
                </div>
            </div>

            <?php foreach($attendance_list as $atd): ?>
            <div class="attendance-card">
                <div>
                    <h4><?= $atd['name'] ?></h4>
                    <p style="font-size:0.8rem; opacity:0.7;"><?= $atd['id'] ?> | <?= $atd['course'] ?> - <?= $atd['section'] ?></p>
                    <div style="margin-top:8px;">
                        <span class="stat-box present">✅ Present: <?= $atd['days_present'] ?></span>
                        <span class="stat-box absent">❌ Absent: <?= $atd['days_absent'] ?></span>
                        <span class="stat-box warning">📊 Rate: <?= $atd['percentage'] ?></span>
                    </div>
                </div>
                <div>
                    <button class="btn-mark btn-present">Present</button>
                    <button class="btn-mark btn-absent">Absent</button>
                    <button class="btn-mark btn-late">Late</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="teacher.js"></script>
</body>
</html>