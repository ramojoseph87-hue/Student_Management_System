<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | SAMS - STAC</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        .settings-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:20px; }
        .settings-card { background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:25px; }
        .settings-card h3 { color:var(--primary); margin-bottom:15px; display:flex; align-items:center; gap:8px; }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-size:0.85rem; font-weight:500; }
        input, select, textarea { width:100%; padding:10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color); }
        .btn-save { background:var(--success); color:white; padding:10px 18px; border:none; border-radius:6px; font-weight:600; cursor:pointer; }
        .danger-zone { border:1px solid var(--danger); }
        .btn-danger { background:var(--danger); color:white; padding:8px 14px; border:none; border-radius:6px; cursor:pointer; }
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
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php" class="active">⚙️ Settings</a></li>
            </ul>
            <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
        </div>

        <div class="main-content">
            <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

            <div class="welcome-card">
                <h1>⚙️ Account Settings</h1>
                <div class="info-row"><span>Manage your profile, account preferences and security settings</span></div>
            </div>

            <div class="settings-grid">
                <!-- Profile Information -->
                <div class="settings-card">
                    <h3>👤 Profile Information</h3>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?= $_SESSION['teacher_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Employee ID / Teacher ID</label>
                        <input type="text" value="<?= $_SESSION['teacher_id'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Department / Program</label>
                        <select>
                            <option selected>Computer Studies Department (BSIS)</option>
                            <option>Education Department (BEEd)</option>
                            <option>Criminology Department (BSCrim)</option>
                            <option>Business Office Admin (BSOA)</option>
                            <option>Business Admin (BSBA)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="joseph@stac.edu.ph">
                    </div>
                    <button class="btn-save">💾 Save Changes</button>
                </div>

                <!-- Change Password -->
                <div class="settings-card">
                    <h3>🔒 Security & Password</h3>
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password">
                    </div>
                    <button class="btn-save">🔑 Update Password</button>
                </div>

                <!-- System Preferences -->
                <div class="settings-card">
                    <h3>⚙️ System Preferences</h3>
                    <div class="form-group">
                        <label>Default View</label>
                        <select>
                            <option>Dashboard</option>
                            <option>My Classes</option>
                            <option>Students List</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Items per page in table</label>
                        <select>
                            <option>10</option>
                            <option selected>20</option>
                            <option>50</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notification Email</label>
                        <select>
                            <option>Enabled</option>
                            <option>Disabled</option>
                        </select>
                    </div>
                    <button class="btn-save">💾 Save Preferences</button>
                </div>

                <!-- Danger Zone -->
                <div class="settings-card danger-zone">
                    <h3 style="color:var(--danger)">⚠️ Danger Zone</h3>
                    <p style="font-size:0.9rem; margin-bottom:15px;">Actions here cannot be undone. Be careful.</p>
                    <button class="btn-danger">🚪 Logout All Devices</button>
                </div>
            </div>
        </div>
    </div>
    <script src="teacher.js"></script>
</body>
</html>