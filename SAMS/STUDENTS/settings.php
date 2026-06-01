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

$success = "";
$error = "";

// ✅ PAGPAPALIT NG PASSWORD
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_pass'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // ✅ TAMA BA ANG DATING PASSWORD? (dito gumagamit tayo ng password_verify kung naka-hash, pero habang simple lang: direktang ikumpara)
    // NOTE: Kapag ginawa na natin ang database, dapat naka-hash ang password. Pansamantala ganito muna.
    if ($current_pass == $user['password']) { // <-- PAPALITAN NATIN ITO NG password_verify PAG MAY DB NA
        if ($new_pass == $confirm_pass) {
            if(strlen($new_pass) >= 6){
        // ✅ I-UPDATE ANG PASSWORD SA DATABASE
        $update = mysqli_query($conn, "UPDATE users SET password = '$new_pass' WHERE user_id = '$user_id'");
        if($update){
            $success = "✅ Password successfully updated!";
        } else {
            $error = "❌ Error updating record: " . mysqli_error($conn);
        }
            } else {
                $error = "❌ New password must be at least 6 characters long.";
            }
    } else {
            $error = "❌ New password and Confirm Password do not match.";
        }
    } else {
        $error = "❌ Current password is incorrect.";
    }
}

// ✅ PAG-UPDATE NG IMPORMASYON (NAME, CONTACT, EMAIL)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $update = mysqli_query($conn, "UPDATE users SET fullname = '$fullname', email = '$email', contact = '$contact', address = '$address' WHERE user_id = '$user_id'");
    
    if($update){
        $success = "✅ Profile information updated successfully!";
        // I-refetch ang data para makita agad ang pagbabago
        $query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
        $user = mysqli_fetch_assoc($query);
    } else {
        $error = "❌ Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Account Settings | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ ESTILO PARA SA SETTINGS PAGE */
        .settings-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
            width: 100%;
        }
        .settings-card {
            flex: 1 1 350px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        .settings-header {
            background-color: #2563EB;
            color: white;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 15px;
        }
        .settings-body {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: transparent;
            color: var(--text-color);
            font-size: 14px;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid #10b981;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        .btn-block {
            width: 100%;
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="app-container">

        <!-- ✅ SIDEBAR - SETTINGS ANG ACTIVE -->
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
                <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
                <li><a href="Profile.php">👤 My Profile</a></li> 
                <li><a href="add_subjects.php">➕ Add Subjects</a></li> 
          
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php">📑 Requirements</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="settings.php" class="active">⚙️ Settings</a></li> <!-- ✅ ACTIVE -->
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

            <!-- ✅ HEADER -->
            <div class="welcome-card">
                <div>
                    <h1>⚙️ Account Settings</h1>
                    <p>Manage your account preferences, security, and personal information</p>
                </div>
            </div>

            <!-- ✅ ALERT MESSAGES -->
            <?php if(!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if(!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <!-- ✅ SETTINGS CARDS - MAGKAKATABI / RESPONSIVE -->
            <div class="settings-container">

                <!-- ✅ CARD 1: PERSONAL INFORMATION -->
                <div class="settings-card">
                    <div class="settings-header">👤 Personal Information</div>
                    <div class="settings-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="fullname" value="<?php echo $user['fullname'] ?? ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact" value="<?php echo $user['contact'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Complete Address</label>
                                <textarea name="address" rows="3"><?php echo $user['address'] ?? ''; ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="btn-primary btn-block">💾 Save Changes</button>
                        </form>
                    </div>
                </div>

                <!-- ✅ CARD 2: CHANGE PASSWORD -->
                <div class="settings-card">
                    <div class="settings-header">🔒 Security & Password</div>
                    <div class="settings-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" placeholder="Enter current password" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" placeholder="Enter new password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" placeholder="Re-type new password" required>
                            </div>
                            <button type="submit" name="change_pass" class="btn-primary btn-block">🔑 Update Password</button>
                        </form>
                    </div>
                </div>

                <!-- ✅ CARD 3: APPEARANCE & PREFERENCES -->
                <div class="settings-card">
                    <div class="settings-header">🎨 Appearance & Display</div>
                    <div class="settings-body">
                        <div class="form-group">
                            <label>Theme Mode</label>
                            <div style="padding: 10px 0; font-size: 14px;">
                                ✔️ Light / Dark Toggle (Top Right Corner)
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Notification Settings</label>
                            <div style="display:flex; justify-content:space-between; align-items:center; padding: 8px 0;">
                                <span>Email Notifications</span>
                                <input type="checkbox" checked style="width:auto;">
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; padding: 8px 0;">
                                <span>System Alerts</span>
                                <input type="checkbox" checked style="width:auto;">
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; padding: 8px 0;">
                                <span>Message Sound</span>
                                <input type="checkbox" style="width:auto;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div> 

    </div> 

    <!-- ✅ SCRIPTS -->
    <script src="../student.js"></script>
    <script>
        const toggle = document.getElementById('darkmode');
        const body = document.body;

        if(localStorage.getItem('darkMode') === 'enabled'){
            body.classList.add('dark-mode');
            toggle.checked = true;
        }

        toggle.addEventListener('change', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', toggle.checked ? 'enabled' : 'disabled');
        });

        function confirmLogout() {
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

</body>
</html>