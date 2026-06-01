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
$student_id = $user['student_id'];

// ✅ SAMPLE DATA
$total_transactions = 2;
$total_amount_paid = 14900.00;
$successful_payments = 2;

$payments = [
    [
        'trans_id' => 'TRN-6A1337',
        'payment_date' => '2026-05-24 14:30:00',
        'description' => 'Tuition Fee - 1st Semester',
        'method' => 'GCASH',
        'ref_number' => '123123123',
        'amount' => 7450.00,
        'status' => 'Success',
        'remarks' => 'Tuition Payment'
    ],
    [
        'trans_id' => 'TRN-6A1338',
        'payment_date' => '2026-05-24 16:15:00',
        'description' => 'Tuition Fee - 1st Semester',
        'method' => 'OTC',
        'ref_number' => '1312423423',
        'amount' => 7450.00,
        'status' => 'Success',
        'remarks' => 'Tuition Payment'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment History | SAMS</title>
    <link rel="stylesheet" href="../style1.css?v=8">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
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
                <li><a href="payment_history.php" class="active">💵 Payment History</a></li>
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

            <!-- ✅ HEADER CARD - INAYOS ANG BACK BUTTON -->
            <div class="welcome-card">
                <div>
                    <h1>💵 Payment History</h1>
                    <p>View all your transactions and enrollment payments</p>
                </div>
                <!-- ✅ INAYOS KO NA ANG BUTTON: TAMA ANG KULAY, HUGIS AT PAGKALAGAY -->
                <a href="Dashboard_Student.php" class="btn-primary">🏠 Back to Dashboard</a>
            </div>

            <!-- ✅ STAT CARDS - GINAGAWA KONG FLEXBOX (MAGKAKATABI / RESPONSIVE) -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3>TOTAL TRANSACTIONS</h3>
                    <div class="num blue"><?php echo $total_transactions; ?></div>
                </div>
                <div class="stat-card">
                    <h3>TOTAL AMOUNT PAID</h3>
                    <div class="num blue">₱ <?php echo number_format($total_amount_paid, 2); ?></div>
                </div>
                <div class="stat-card">
                    <h3>SUCCESSFUL PAYMENTS</h3>
                    <div class="num blue"><?php echo $successful_payments; ?></div>
                </div>
                <div class="stat-card">
                    <h3>STATUS</h3>
                    <div class="num" style="color: #10b981;">Verified</div>
                </div>
            </div>

            <!-- ✅ TRANSACTION LIST - MALINIS AT GANDA TIGNAN -->
            <div class="announcement-card">
                <h2>📋 Transaction Records</h2>

                <?php foreach($payments as $pay): ?>
                <div class="announcement-item">
                    <div class="trans-left">
                        <h4>
                            <?php echo $pay['description']; ?> 
                            <span class="status-badge <?php echo strtolower($pay['status']); ?>">
                                <?php echo $pay['status']; ?>
                            </span>
                        </h4>
                        <p class="trans-meta">
                            Ref: <span><?php echo $pay['ref_number']; ?></span> | 
                            Method: <span><?php echo strtoupper($pay['method']); ?></span>
                        </p>
                        <small class="trans-date">Date: <?php echo date("F d, Y | h:i A", strtotime($pay['payment_date'])); ?></small>
                    </div>
                    <div class="trans-right">
                        <strong>₱ <?php echo number_format($pay['amount'], 2); ?></strong>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        </div> 

    </div> 

    <script src="../student.js"></script>

    <!-- ✅ DARK MODE SCRIPT (PARA SIGURADO GUMAGANA) -->
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