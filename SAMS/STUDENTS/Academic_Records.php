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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Academic Records | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
</head>
<body>

    <!-- ✅ DARK MODE SWITCH -->
    <div class="mode-switch">
        <span>☀️</span>
        <label class="switch">
            <input type="checkbox" id="darkmode">
            <span class="slider"></span>
        </label>
        <span>🌙</span>
    </div>

    <!-- ✅ SIDEBAR - KUMPLETO -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../untitled.png" alt="School Logo">
            <h2>SAMS</h2>
            <p>Saint Thomas Aquinas College</p>
            <hr>
            <p>Welcome, <?php echo ucwords($user['firstname']); ?>!</p>
        </div>

        <ul class="nav-links">
            <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
            <li><a href="Profile.php">👤 My Profile</a></li>
            <li><a href="subjectenrolled.php">📚 Subjects Enrolled</a></li>
            <li><a href="cllasssched.php">🗓️ Class Schedule</a></li>
            <li><a href="view.php">📝 View Grades</a></li>
            <li><a href="Academic_Records.php" class="active">📁 Academic Records</a></li>
            <li><a href="payment_history.php">💵 Payment History</a></li>
            <li><a href="requirements.php">📑 Requirements</a></li>
            <li><a href="Announcements.php">🔔 Announcements</a></li>
            <li><a href="Message.php">✉️ Messages</a></li>
            <li><a href="Help_Us.php">❓ Help & Support</a></li>
        </ul>

        <div class="logout-btn">
            <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
        </div>
    </div>

    <!-- ✅ MAIN CONTENT - MAGKAKATABI PERO RESPONSIVE -->
    <div class="main-content">
        <div class="welcome-card">
            <h1>📁 Academic Records</h1>
            <p>Official documents and history of your academic performance</p>
        </div>

        <div class="records-container">
            
            <!-- 📄 Transcript of Records -->
            <div class="record-card">
                <div class="record-header">
                    <h3>📄 Transcript of Records</h3>
                </div>
                <div class="record-body">
                    <div class="record-row">
                        <span class="label">Status:</span>
                        <span class="value">Available</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Last Updated:</span>
                        <span class="value">May 20, 2026</span>
                    </div>
                    <div class="record-row">
                        <span class="label">School Year:</span>
                        <span class="value">2025-2026</span>
                    </div>
                    <button class="download-btn">⬇️ Download PDF</button>
                </div>
            </div>

            <!-- 📑 Certificate of Grades -->
            <div class="record-card">
                <div class="record-header">
                    <h3>📑 Certificate of Grades</h3>
                </div>
                <div class="record-body">
                    <div class="record-row">
                        <span class="label">Status:</span>
                        <span class="value">Available</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Last Updated:</span>
                        <span class="value">May 20, 2026</span>
                    </div>
                    <div class="record-row">
                        <span class="label">School Year:</span>
                        <span class="value">2025-2026</span>
                    </div>
                    <button class="download-btn">⬇️ Download PDF</button>
                </div>
            </div>

            <!-- 📜 Certificate of Enrollment -->
            <div class="record-card">
                <div class="record-header">
                    <h3>📜 Certificate of Enrollment</h3>
                </div>
                <div class="record-body">
                    <div class="record-row">
                        <span class="label">Status:</span>
                        <span class="value">Available</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Last Updated:</span>
                        <span class="value">June 1, 2026</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Semester:</span>
                        <span class="value">2nd Semester</span>
                    </div>
                    <button class="download-btn">⬇️ Download PDF</button>
                </div>
            </div>

            <!-- 🎓 Certificate of Good Moral -->
            <div class="record-card">
                <div class="record-header">
                    <h3>🎓 Certificate of Good Moral</h3>
                </div>
                <div class="record-body">
                    <div class="record-row">
                        <span class="label">Status:</span>
                        <span class="value">Available</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Last Updated:</span>
                        <span class="value">May 15, 2026</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Valid Until:</span>
                        <span class="value">End of School Year</span>
                    </div>
                    <button class="download-btn">⬇️ Download PDF</button>
                </div>
            </div>

            <!-- ⭐ Rating Sheet -->
            <div class="record-card">
                <div class="record-header">
                    <h3>⭐ Rating Sheet</h3>
                </div>
                <div class="record-body">
                    <div class="record-row">
                        <span class="label">Status:</span>
                        <span class="value">Available</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Last Updated:</span>
                        <span class="value">June 5, 2026</span>
                    </div>
                    <div class="record-row">
                        <span class="label">Evaluation:</span>
                        <span class="value">Mid & Final</span>
                    </div>
                    <button class="download-btn">⬇️ Download PDF</button>
                </div>
            </div>

        </div>
    </div>

    <!-- ✅ JAVASCRIPT -->
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