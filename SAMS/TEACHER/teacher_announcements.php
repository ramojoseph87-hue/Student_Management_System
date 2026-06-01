<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

$announcements = [
    ['id'=>1, 'title'=>'Deadline of Submission', 'msg'=>'All projects must be submitted on or before June 15, 2026. No extensions will be granted.', 'date'=>'June 1, 2026', 'type'=>'Important', 'audience'=>'All Courses'],
    ['id'=>2, 'title'=>'No Classes Holiday', 'msg'=>'There will be no classes on June 12, 2026 in observance of Independence Day.', 'date'=>'May 30, 2026', 'type'=>'Holiday', 'audience'=>'All'],
    ['id'=>3, 'title'=>'BSIS Seminar', 'msg'=>'BS Information System students are required to attend the IT Seminar on June 8, 8:00 AM at the Gym.', 'date'=>'May 28, 2026', 'type'=>'Event', 'audience'=>'BS Information System'],
    ['id'=>4, 'title'=>'BEEd Workshop', 'msg'=>'Workshop for Elementary Education students regarding teaching strategies.', 'date'=>'May 25, 2026', 'type'=>'Activity', 'audience'=>'BEEd'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | SAMS - STAC</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        .ann-card { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:20px; margin-bottom:15px; transition:transform 0.2s; }
        .ann-card:hover { transform:translateY(-3px); }
        .ann-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
        .type-badge { padding:3px 8px; border-radius:4px; font-size:0.7rem; font-weight:bold; }
        .type-important { background:var(--danger); color:white; }
        .type-holiday { background:var(--warning); color:white; }
        .type-event { background:var(--success); color:white; }
        .audience { font-size:0.75rem; color:var(--gray); font-style:italic; }
        .new-ann { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:20px; margin-bottom:25px; }
        textarea, input { width:100%; padding:10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color); margin:8px 0; }
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
                <li><a href="teacher_attendance.php">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php" class="active">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
            </ul>
            <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
        </div>

        <div class="main-content">
            <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

            <div class="welcome-card">
                <h1>📢 Announcements & Advisory</h1>
                <div class="info-row"><span>Create and manage announcements for students and specific courses</span></div>
            </div>

            <!-- Create New Announcement -->
            <div class="new-ann">
                <h3>📝 Create New Announcement</h3>
                <input type="text" placeholder="Title / Subject">
                <textarea rows="3" placeholder="Type your message here..."></textarea>
                <div style="display:flex; gap:10px; align-items:center;">
                    <select style="padding:8px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                        <option>Visible to: All Courses</option>
                        <option>BS Information System</option>
                        <option>BEEd</option>
                        <option>BSCrim</option>
                        <option>BSOA</option>
                        <option>BSBA</option>
                    </select>
                    <button class="action-btn" style="margin-left:auto;">📤 Post Announcement</button>
                </div>
            </div>

            <!-- List of Announcements -->
            <h3 style="margin-bottom:15px;">📋 Posted Announcements</h3>
            <?php foreach($announcements as $ann): ?>
            <div class="ann-card">
                <div class="ann-header">
                    <h4 style="color:var(--primary); margin:0;"><?= $ann['title'] ?></h4>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <span class="type-badge type-<?= strtolower($ann['type']) ?>"><?= $ann['type'] ?></span>
                        <span style="font-size:0.75rem; opacity:0.7;"><?= $ann['date'] ?></span>
                    </div>
                </div>
                <p style="margin:10px 0; line-height:1.5;"><?= $ann['msg'] ?></p>
                <div class="audience">🎯 Target: <?= $ann['audience'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="teacher.js"></script>
</body>
</html>