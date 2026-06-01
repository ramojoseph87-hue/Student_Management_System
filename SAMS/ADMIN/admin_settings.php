<?php
session_start();
include "../STUDENTS/config.php";

// ✅ SAVE SETTINGS LOGIC
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // School Info
    if (isset($_POST['save_school_info'])) {
        $_SESSION['school_name'] = trim($_POST['school_name']);
        $_SESSION['school_address'] = trim($_POST['school_address']);
        $_SESSION['school_contact'] = trim($_POST['school_contact']);
        $_SESSION['school_email'] = trim($_POST['school_email']);
        header("Location: admin_settings.php?success=1");
        exit;
    }

    // Admin Account
    if (isset($_POST['save_account'])) {
        $_SESSION['admin_fullname'] = trim($_POST['admin_fullname']);
        $_SESSION['admin_username'] = trim($_POST['admin_username']);
        if (!empty($_POST['new_password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $_SESSION['admin_password'] = $_POST['new_password'];
                header("Location: admin_settings.php?success=2");
                exit;
            } else {
                $error = "mismatch";
            }
        } else {
            header("Location: admin_settings.php?success=2");
            exit;
        }
    }

    // Appearance / Theme
    if (isset($_POST['save_theme'])) {
        $_SESSION['default_mode'] = $_POST['default_mode'];
        $_SESSION['sidebar_style'] = $_POST['sidebar_style'];
        header("Location: admin_settings.php?success=3");
        exit;
    }

    // Backup & Reset
    if (isset($_POST['reset_system'])) {
        header("Location: admin_settings.php?success=4");
        exit;
    }
}

// ✅ DEFAULT VALUES kung wala pa sa session
if (!isset($_SESSION['school_name'])) $_SESSION['school_name'] = "SCHOOL ACADEMIC MANAGEMENT SYSTEM";
if (!isset($_SESSION['school_address'])) $_SESSION['school_address'] = "123 Education Street, Quezon City, Metro Manila";
if (!isset($_SESSION['school_contact'])) $_SESSION['school_contact'] = "(02) 1234 5678 | +63 917 123 4567";
if (!isset($_SESSION['school_email'])) $_SESSION['school_email'] = "info@school.edu.ph";
if (!isset($_SESSION['admin_fullname'])) $_SESSION['admin_fullname'] = "System Administrator";
if (!isset($_SESSION['admin_username'])) $_SESSION['admin_username'] = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
/* ✅ EKSAKTONG KULAY - GAYA SA DASHBOARD */
:root {
    --primary: #2563EB; /* ✅ EKSAKTO KULAY NG DASHBOARD */
    --primary-light: #3B82F6;
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --gray: #64748B;

    --bg-color: #F3F4F6; /* ✅ EKSAKTO BACKGROUND */
    --card-bg: #FFFFFF;
    --text-color: #1F2937;
    --border-color: #E5E7EB;
    --sidebar-bg: #FFFFFF;
    --sidebar-text: #1F2937;
}

/* ✅ DARK MODE */
body.dark-mode {
    --primary: #3B82F6 !important;
    --primary-light: #60A5FA !important;
    --success: #10B981 !important;
    --warning: #F59E0B !important;
    --danger: #EF4444 !important;
    --gray: #9CA3AF !important;

    --bg-color: #111827 !important;
    --card-bg: #1F2937 !important;
    --text-color: #F9FAFB !important;
    --border-color: #374151 !important;
    --sidebar-bg: #1F2937 !important;
    --sidebar-text: #F9FAFB !important;

    color: var(--text-color) !important;
    background-color: var(--bg-color) !important;
}

* { margin: 0; padding: 0; box-sizing: border-box; transition: all 0.3s ease; font-family: 'Segoe UI, Roboto, sans-serif; }
body { background-color: var(--bg-color); color: var(--text-color); overflow-x: hidden; }
.app-container { display: flex; min-height: 100vh; position: relative; }

/* ✅ SIDEBAR */
.sidebar { 
    width: 260px; 
    background-color: var(--sidebar-bg) !important; 
    border-right: 1px solid var(--border-color); 
    position: fixed; 
    height: 100vh; 
    display: flex; 
    flex-direction: column; 
    justify-content: space-between; 
    overflow-y: auto; 
    z-index: 100; 
}
.sidebar-header { padding: 20px 16px; text-align: center; border-bottom: 1px solid var(--border-color); margin-bottom: 10px; flex-shrink: 0; }
.sidebar-header img { width: 60px; height: 60px; object-fit: contain; margin-bottom: 8px; }
.sidebar-header h2 { font-size: 1.1rem; color: var(--primary); font-weight: 700; }
.sidebar-header p { font-size: 0.75rem; color: var(--gray); }
.sidebar-header hr { border: none; border-top: 1px solid var(--border-color); margin: 10px 0; }
.sidebar-header p.welcome-text { color:#93C5FD; font-weight:500; font-size:14px; }

.nav-links { list-style: none; padding: 0 8px; flex-grow: 1; overflow-y: auto; }
.nav-links li { margin-bottom: 2px; }
.nav-links li a { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--sidebar-text); text-decoration: none; font-size: 0.9rem; border-radius: 6px; margin: 0 4px; opacity: 0.8; }
.nav-links li a:hover { background-color: rgba(37, 99, 235, 0.08); color: var(--primary); opacity: 1; }
.nav-links li a.active { background-color: rgba(37, 99, 235, 0.12); color: var(--primary); font-weight: 600; opacity: 1; }

.logout-btn { padding: 12px 12px 20px 12px; margin-top: auto; border-top: 1px solid var(--border-color); background-color: var(--sidebar-bg); flex-shrink: 0; }
.logout-btn a { display: block; padding: 12px; background-color: var(--danger) !important; color: white !important; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
.logout-btn a:hover { opacity: 0.9; }

/* ✅ MAIN CONTENT */
.main-content { margin-left: 260px; flex: 1; min-height: 100vh; padding: 20px 24px; background-color: var(--bg-color) !important; }
.mode-switch { position: fixed; top: 20px; right: 30px; z-index: 99; display: flex; align-items: center; gap: 8px; color: var(--text-color); background-color: var(--card-bg); padding: 6px 10px; border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.switch { position: relative; display: inline-block; width: 44px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); border-radius: 20px; transition: .4s; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(22px); }

/* ✅ WELCOME CARD - EKSAKTO KOPYA NG DASHBOARD */
.welcome-card { 
    background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%); 
    color: white; 
    padding: 28px 32px; 
    border-radius: 12px; 
    margin-bottom: 24px; 
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}
.welcome-card h1 { font-size: 1.5rem; margin-bottom: 8px; font-weight: 700; }
.info-row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; font-size: 0.9rem; opacity: 0.95; margin-top: 6px; }
.semester-badge { background: rgba(255,255,255,0.22); padding: 3px 10px; border-radius: 12px; font-weight:500; font-size:0.8rem; }
.type-regular { color: #BBF7D0; font-weight: 500; font-size:0.8rem; }

/* ✅ SETTINGS DESIGN */
.settings-container { display: flex; flex-direction: column; gap: 20px; }
.settings-card { 
    background-color: var(--card-bg) !important; 
    border: 1px solid var(--border-color) !important; 
    border-radius: 12px; 
    padding: 24px; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: transform 0.2s; 
}
.settings-card:hover { transform: translateY(-3px); }
.settings-card h2 { 
    font-size: 1.1rem; 
    color: var(--primary); 
    margin-bottom: 20px; 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    border-bottom: 1px solid var(--border-color); 
    padding-bottom: 10px; 
}

.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-bottom: 16px; }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-size: 0.85rem; font-weight: 600; color: var(--text-color); }
.required { color: var(--danger); }
input, select, textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--border-color) !important;
    border-radius: 6px;
    background: var(--bg-color) !important;
    color: var(--text-color) !important;
    font-size: 0.9rem;
}
textarea { min-height: 80px; resize: vertical; }

.action-btn { 
    padding: 10px 18px; 
    background: var(--success); 
    color: white; 
    border: none; 
    border-radius: 8px; 
    font-weight: 600; 
    cursor: pointer; 
    transition: background 0.2s; 
    text-decoration: none; 
    font-size: 0.85rem; 
}
.action-btn:hover { background: #059669; }
.btn-danger { background: var(--danger); }
.btn-danger:hover { background: #DC2626; }

.alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 0.85rem; }
.alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
.alert-danger { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

@media (max-width: 768px) { .sidebar { width: 0; transform: translateX(-100%); } .sidebar.active { width: 260px; transform: translateX(0); } .main-content { margin-left: 0; width: 100%; padding: 12px; } .form-grid { grid-template-columns: 1fr; } }
    </style>
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
                <p class="welcome-text">👨‍💻 Welcome, Administrator!</p>
            </div>

            <ul class="nav-links">
                <li><a href="admindashboard.php">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php">📂 Requirements</a></li>
                <li><a href="admin_settings.php" class="active">⚙️ System Settings</a></li>
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

            <!-- ✅ WELCOME CARD - EKSAKTO NA KAPAREHO NG DASHBOARD -->
            <div class="welcome-card">
                <h1>⚙️ System Settings</h1>
                <div class="info-row">
                    <span>Configure school information, account preferences, and system defaults</span>
                    <span class="semester-badge">Management Portal</span>
                    <span class="type-regular">Full Access</span>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if($_GET['success'] == 1): ?> ✅ School Information updated successfully! <?php endif; ?>
                <?php if($_GET['success'] == 2): ?> ✅ Account settings saved successfully! <?php endif; ?>
                <?php if($_GET['success'] == 3): ?> ✅ Appearance settings applied! <?php endif; ?>
                <?php if($_GET['success'] == 4): ?> ✅ System reset completed! <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error) && $error == 'mismatch'): ?>
            <div class="alert alert-danger">❌ Error: Passwords do not match!</div>
            <?php endif; ?>

            <div class="settings-container">
                <!-- ✅ SCHOOL INFORMATION -->
                <div class="settings-card">
                    <h2>🏫 School Information</h2>
                    <form method="POST" action="admin_settings.php">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>School Name <span class="required">*</span></label>
                                <input type="text" name="school_name" value="<?= $_SESSION['school_name'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>School Address <span class="required">*</span></label>
                                <input type="text" name="school_address" value="<?= $_SESSION['school_address'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Number <span class="required">*</span></label>
                                <input type="text" name="school_contact" value="<?= $_SESSION['school_contact'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Official Email <span class="required">*</span></label>
                                <input type="email" name="school_email" value="<?= $_SESSION['school_email'] ?>" required>
                            </div>
                        </div>
                        <button type="submit" name="save_school_info" class="action-btn">💾 Save School Information</button>
                    </form>
                </div>

                <!-- ✅ ADMIN ACCOUNT SETTINGS -->
                <div class="settings-card">
                    <h2>👤 Administrator Account</h2>
                    <form method="POST" action="admin_settings.php">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Full Name <span class="required">*</span></label>
                                <input type="text" name="admin_fullname" value="<?= $_SESSION['admin_fullname'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Username <span class="required">*</span></label>
                                <input type="text" name="admin_username" value="<?= $_SESSION['admin_username'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>New Password <small>(Leave blank to keep current)</small></label>
                                <input type="password" name="new_password" placeholder="Enter new password...">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" placeholder="Re-type new password...">
                            </div>
                        </div>
                        <button type="submit" name="save_account" class="action-btn">💾 Update Account Details</button>
                    </form>
                </div>

                <!-- ✅ APPEARANCE & THEME -->
                <div class="settings-card">
                    <h2>🎨 Appearance & Display</h2>
                    <form method="POST" action="admin_settings.php">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Default Theme Mode</label>
                                <select name="default_mode">
                                    <option value="light">☀️ Light Mode</option>
                                    <option value="dark">🌙 Dark Mode</option>
                                    <option value="auto">🔄 Auto (Follow System)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Sidebar Style</label>
                                <select name="sidebar_style">
                                    <option value="fixed">📌 Fixed Sidebar</option>
                                    <option value="collapsed">📏 Collapsed by Default</option>
                                    <option value="minimal">⚫ Minimal Icons Only</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="save_theme" class="action-btn">💾 Save Display Settings</button>
                    </form>
                </div>

                <!-- ✅ BACKUP & RESET -->
                <div class="settings-card">
                    <h2>💾 Backup & System Reset</h2>
                    <p style="font-size:0.85rem; color:var(--gray); margin-bottom:16px;">Warning: These actions affect all data. Proceed with caution.</p>
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button class="action-btn">📤 Export / Backup Database</button>
                        <button class="action-btn">📥 Import Data</button>
                        <button type="submit" name="reset_system" class="action-btn btn-danger" onclick="return confirm('⚠️ WARNING: This will reset ALL settings to default! Continue?')">🔄 Reset All Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ SCRIPT -->
    <script src="../admin_script.js"></script>
    <script>
        const toggle = document.getElementById('darkmode');
        
        if(localStorage.getItem('darkMode') === 'true') { 
            document.body.classList.add('dark-mode'); 
            toggle.checked = true; 
        } else {
            document.body.classList.remove('dark-mode'); 
            toggle.checked = false;
        }

        toggle.addEventListener('change', function() { 
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'false');
            }
        });
    </script>
</body>
</html>