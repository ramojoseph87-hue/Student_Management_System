<?php
session_start();
// ✅ KONEKTA SA DATABASE (MySQLi, pareho sa gamit mo)
include "../STUDENTS/config.php"; 

// ✅ KUNIN ANG BILANG, KUNG WALA HINDI MAGKAKA-ERROR
$total_students = 0;
$total_teachers = 0;
$total_users = 0;

// Try to count, kung wala table, dedma lang
try {
    // Students
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
    if($res) $r = mysqli_fetch_assoc($res); $total_students = $r['total'] ?? 0;

    // Teachers
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM teachers");
    if($res) $r = mysqli_fetch_assoc($res); $total_teachers = $r['total'] ?? 0;

    // Users
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM system_users");
    if($res) $r = mysqli_fetch_assoc($res); $total_users = $r['total'] ?? 0;

} catch(Exception $e) {
    // Kung wala pa, lalabas lang 0, walang error
}

$stats = [
    'total_students' => $total_students,
    'total_teachers' => $total_teachers,
    'total_users' => $total_users,
    'pending_req' => 32,
    'announcements' => 8
];

$recent_activities = [
    ['action' => 'New Announcement Posted', 'by' => 'Registrar', 'time' => '10 mins ago'],
    ['action' => 'Student Requirements Approved', 'by' => 'Admission', 'time' => '30 mins ago'],
    ['action' => 'New Student Registered', 'by' => 'System', 'time' => '1 hour ago'],
    ['action' => 'Grades Submitted', 'by' => 'CS Dept.', 'time' => '2 hours ago'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
</head>
<body>
    <div class="app-container">
        <!-- ✅ SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="School Logo">
                <h2>SAMS - ADMIN</h2>
                <p>System Management Portal</p>
                <hr>
                <p style="color:#BFDBFE; font-weight:bold; font-size:14px;">👨‍💻 Welcome, Administrator!</p>
            </div>

            <ul class="nav-links">
                <li><a href="admindashboard.php" class="active">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php">📂 Requirements</a></li>
                <li><a href="admin_settings.php">⚙️ System Settings</a></li>
            </ul>

            <div class="logout-btn">
                <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
            </div>
        </div>

        <!-- ✅ MAIN CONTENT -->
        <div class="main-content">
            <div class="mode-switch">
                <span>☀️</span>
                <label class="switch">
                    <input type="checkbox" id="darkmode">
                    <span class="slider"></span>
                </label>
                <span>🌙</span>
            </div>

            <div class="welcome-card">
                <h1>Welcome Back, Administrator!</h1>
                <div class="info-row">
                    <span>System Academic Year 2025-2026</span>
                    <span class="semester-badge">Management Portal</span>
                    <span class="type-regular">Full Access</span>
                </div>
            </div>

            <!-- ✅ STATISTICS -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3>TOTAL STUDENTS</h3>
                    <div class="num blue"><?php echo $stats['total_students']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>TOTAL TEACHERS</h3>
                    <div class="num green"><?php echo $stats['total_teachers']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>SYSTEM USERS</h3>
                    <div class="num red"><?php echo $stats['total_users']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>ANNOUNCEMENTS</h3>
                    <div class="num blue"><?php echo $stats['announcements']; ?></div>
                </div>
            </div>

            <div class="announcement-card">
                <h2>📅 Recent System Activities <small>• Logs</small></h2>
                <?php foreach($recent_activities as $act): ?>
                <div class="announcement-item">
                    <div>
                        <h4><?php echo $act['action']; ?></h4>
                        <p>Processed by: <?php echo $act['by']; ?></p>
                    </div>
                    <small><?php echo $act['time']; ?></small>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="announcement-card">
                <h2>⚡ Quick Actions</h2>
                <div class="stats-row" style="grid-template-columns: 1fr 1fr 1fr 1fr;">
                    <button class="action-btn" onclick="window.location.href='admin_people.php?tab=Students'" style="padding: 15px; border: none; border-radius: 6px; background-color: var(--primary-light); color: white; font-weight: 600; cursor: pointer;">+ New Student</button>
                    <button class="action-btn" style="padding: 15px; border: none; border-radius: 6px; background-color: var(--primary-light); color: white; font-weight: 600; cursor: pointer;">+ Post Announcement</button>
                    <button class="action-btn" style="padding: 15px; border: none; border-radius: 6px; background-color: var(--primary-light); color: white; font-weight: 600; cursor: pointer;">📤 Upload Grades</button>
                    <button class="action-btn" style="padding: 15px; border: none; border-radius: 6px; background-color: var(--primary-light); color: white; font-weight: 600; cursor: pointer;">📊 Generate Report</button>
                </div>

                <div style="margin-top:25px; padding: 15px; border-top: 1px solid var(--border-color);">
                    <h3 style="font-size:15px; color: var(--primary-light); margin-bottom: 12px;">System Status</h3>
                    <div style="font-size:14px; padding:8px 0; display:flex; justify-content:space-between; border-bottom:1px solid var(--border-color);">
                        <span>Database:</span> <span style="color:#10B981; font-weight:bold;">● Connected</span>
                    </div>
                    <div style="font-size:14px; padding:8px 0; display:flex; justify-content:space-between; border-bottom:1px solid var(--border-color);">
                        <span>Storage:</span> <span style="color:#F59E0B; font-weight:bold;">65% Used</span>
                    </div>
                    <div style="font-size:14px; padding:8px 0; display:flex; justify-content:space-between;">
                        <span>Last Backup:</span> <span style="color:#3B82F6; font-weight:bold;">Today 02:00 AM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../admin_script.js"></script>
</body>
</html>