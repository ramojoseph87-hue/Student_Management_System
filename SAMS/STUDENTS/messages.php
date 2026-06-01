<?php
session_start();
include 'config.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// ✅ SAMPLE DATA
$messages = [
    ['id'=>1, 'from'=>'Registrar Office', 'subject'=>'Enrollment Concern', 'date'=>'May 25, 2026', 'content'=>'Please submit your requirements.', 'is_read'=>false],
    ['id'=>2, 'from'=>'Adviser - Mr. Santos', 'subject'=>'Class Schedule Update', 'date'=>'May 24, 2026', 'content'=>'Change of room for tomorrow.', 'is_read'=>true],
    ['id'=>3, 'from'=>'Cashier', 'subject'=>'Payment Confirmation', 'date'=>'May 20, 2026', 'content'=>'Payment successfully recorded.', 'is_read'=>true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
</head>
<body>
<div class="app-container">
    <!-- ✅ SIDEBAR (KOPYA MO LANG GAYA SA IBA) -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../untitled.png" alt="School Logo">
            <h2>SAMS</h2>
            <p>Saint Thomas Aquinas College</p>
            <hr>
            <p>Welcome, <?php echo explode(' ', $user['fullname'])[0]; ?>!</p>
        </div>
        <ul class="nav-links">
            <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
            <li><a href="Profile.php">👤 My Profile</a></li> 
            <li><a href="add_subjects.php">➕ Add Subjects</a></li> 
         
            <li><a href="classssched.php">🗓️ Class Schedule</a></li>
            <li><a href="view.php">📝 View Grades</a></li>
            <li><a href="Academic_Records.php">📁 Academic Records</a></li>
            <li><a href="payment_history.php">💵 Payment History</a></li>
            <li><a href="messages.php" class="active">📩 Messages</a></li>
            <li><a href="requirements.php">📑 Requirements</a></li>
            <li><a href="Announcements.php">🔔 Announcements</a></li>
            <li><a href="settings.php">⚙️ Settings</a></li>
            <li><a href="help.php">❓ Help & Support</a></li>
        </ul>
        <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
    </div>

    <!-- ✅ MAIN CONTENT -->
    <div class="main-content">
        <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

        <div class="welcome-card">
            <div><h1>📩 Messages</h1><p>Communications from Administration, Advisers, and Offices</p></div>
            <a href="#" class="btn-primary">✉️ Compose New</a>
        </div>

        <div class="announcement-card">
            <h2>📥 Inbox</h2>
            <?php foreach($messages as $msg): ?>
            <div class="announcement-item" style="border-left: 4px solid <?php echo $msg['is_read'] ? '#3B82F6' : '#EAB308'; ?>;">
                <div class="trans-left">
                    <h4 style="font-weight: <?php echo $msg['is_read'] ? '500' : '700'; ?>;">
                        <?php echo $msg['from']; ?>
                        <?php if(!$msg['is_read']) echo '<span class="status-badge success">NEW</span>'; ?>
                    </h4>
                    <p class="trans-meta"><strong><?php echo $msg['subject']; ?></strong></p>
                    <small class="trans-date"><?php echo $msg['content']; ?></small>
                </div>
                <div class="trans-right">
                    <small><?php echo $msg['date']; ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script src="../student.js"></script>
<script>
const toggle = document.getElementById('darkmode'); if(localStorage.getItem('darkMode')==='enabled'){document.body.classList.add('darkmode');toggle.checked=true;}
toggle.addEventListener('change',()=>{document.body.classList.toggle('dark-mode');localStorage.setItem('darkMode',toggle.checked?'enabled':'disabled');});
function confirmLogout(){if(confirm("Are you sure?"))window.location.href="logout.php";}
</script>
</body>
</html>