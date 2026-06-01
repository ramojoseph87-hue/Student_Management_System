<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Profile | SAMS</title>
    <link rel="stylesheet" href="../style1.css?v=2">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ PARA SA CARD NG PROFILE - KATUGMA NG IBA PANG PAGE */
        .profile-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .profile-header {
            font-size: 1.1rem;
            color: var(--text-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* ✅ TABLE DESIGN - SOSYAL, MALINAW, TUGMA SA LIGHT/DARK MODE */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 15px 20px;
            text-align: left;
            font-size: 14px;
            line-height: 1.5;
            border-bottom: 1px solid var(--border-color);

padding-left: 24px;
        }

        .data-table th {
            background-color: rgba(59, 130, 246, 0.05);
            font-weight: 600;
            color: var(--primary-light);
            width: 220px;
            white-space: nowrap;
        }

        .data-table td {
            background-color: var(--card-bg);
            color: var(--text-color);
            font-weight: 500;
        }

        /* ✅ PARA SA HULING ROW - WALA NANG BORDER SA ILALIM */
        .data-table tr:last-child th,
        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* ✅ EFFECT KAPAG DINADAANAN NG MOUSE */
        .data-table tr:hover td {
            background-color: rgba(59, 130, 246, 0.03);
        }

        /* ✅ RESPONSIVE - MAGIGING VERTICAL SA CP */
        @media (max-width: 768px) {
            .data-table, .data-table tbody, .data-table tr, .data-table th, .data-table td {
                display: block;
                width: 100%;
            }
            .data-table th {
                border-bottom: none;
                padding-bottom: 5px;
            }
            .data-table td {
                padding-top: 5px;
                margin-bottom: 12px;
                border-bottom: 1px solid var(--border-color) !important;
            }
        }
    </style>
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
                <p>Welcome, <strong><?php echo $_SESSION['fullname'] ?? 'Student'; ?></strong>!</p>
            </div>

            <ul class="nav-links">
                <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
                <li><a href="Profile.php" class="active">👤 My Profile</a></li> 
                <li><a href="add_subjects.php">➕ Add Subjects</a></li>   
                <li><a href="classsched.php">🗓️ Class Schedule</a></li>
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
                <h1>👤 My Profile</h1>
                <p>View and manage your personal information</p>
            </div>

            <!-- ✅ STUDENT INFO CARD -->
            <div class="profile-card">
                <h2 class="profile-header">Student Information</h2>

                <table class="data-table">
                   <tr>
                        <th>Student ID Number</th>
                        <td><strong><?php echo $_SESSION['student_id'] ?? 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                    <td><strong><?php echo $_SESSION['fullname'] ?? 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Course / Program</th>
                        <td><strong><?php echo isset($_SESSION['course']) ? strtoupper($_SESSION['course']) : 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Year Level</th>
                        <td><strong><?php echo $_SESSION['year_level'] ?? 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Section</th>
                        <td><strong><?php echo $_SESSION['section'] ?? 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><strong><?php echo $_SESSION['username'] ?? 'N/A'; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td><strong><?php echo $_SESSION['email'] ?? 'N/A'; ?></strong></td>
                    </tr>
                </table>
            </div>
        </div> 

    </div> <!-- /App Container -->

    <script src="../student.js"></script>
    <script>
        const toggle = document.getElementById('darkmode');
        const body = document.body;

        // ✅ LOAD DARK MODE SETTING (KATUGMA NG IBA PANG PAGE)
        if(localStorage.getItem('darkMode') === 'enabled'){
            body.classList.add('dark-mode');
            toggle.checked = true;
        } else {
            body.classList.remove('dark-mode');
            toggle.checked = false;
        }

        // ✅ TOGGLE FUNCTION
        toggle.addEventListener('change', () => {
            if(toggle.checked){
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // ✅ LOGOUT FUNCTION
        function confirmLogout() {
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>