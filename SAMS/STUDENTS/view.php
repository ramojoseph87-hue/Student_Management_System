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

// ✅ SAMPLE DATA NG MGA GRADES - PAPALITAN NATIN NG DATABASE PAG MAY TABLE NA
$grades = [
    [
        'code' => 'CC 101',
        'subject' => 'Introduction to Computing',
        'units' => 3,
        'prelim' => 90,
        'midterm' => 92,
        'semi' => 91,
        'final' => 93,
        'final_grade' => 92,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'CS 102',
        'subject' => 'Computer Programming 1',
        'units' => 3,
        'prelim' => 85,
        'midterm' => 88,
        'semi' => 87,
        'final' => 90,
        'final_grade' => 88,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'GE 101',
        'subject' => 'Understanding the Self',
        'units' => 3,
        'prelim' => 91,
        'midterm' => 93,
        'semi' => 92,
        'final' => 95,
        'final_grade' => 93,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'MATH 101',
        'subject' => 'College Algebra',
        'units' => 3,
        'prelim' => 78,
        'midterm' => 81,
        'semi' => 85,
        'final' => 88,
        'final_grade' => 83,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'IT 101',
        'subject' => 'IT Fundamentals',
        'units' => 3,
        'prelim' => 89,
        'midterm' => 90,
        'semi' => 88,
        'final' => 91,
        'final_grade' => 90,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'PE 101',
        'subject' => 'Physical Education 1',
        'units' => 2,
        'prelim' => 93,
        'midterm' => 94,
        'semi' => 95,
        'final' => 95,
        'final_grade' => 94,
        'remarks' => 'PASSED'
    ],
    [
        'code' => 'NSTP 1',
        'subject' => 'Civic Welfare Training Service',
        'units' => 3,
        'prelim' => 88,
        'midterm' => 90,
        'semi' => 89,
        'final' => 92,
        'final_grade' => 90,
        'remarks' => 'PASSED'
    ]
];

// ✅ KUWENTA NG GENERAL AVERAGE AT TOTAL UNITS
$total_units = 0;
$total_grade_points = 0;

foreach($grades as $g){
    $total_units += $g['units'];
    $total_grade_points += ($g['final_grade'] * $g['units']);
}
$general_average = ($total_units > 0) ? round($total_grade_points / $total_units, 2) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>View Grades | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ CUSTOM STYLE PARA SA GRADES PAGE */
        .grades-container {
            margin-top: 20px;
            width: 100%;
            overflow-x: auto;
        }
        .summary-card {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1 1 180px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .summary-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #2563EB;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #2563EB;
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-size: 13px;
            white-space: nowrap;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }
        tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        .grade-num {
            font-weight: 600;
            text-align: center;
        }
        /* ✅ KULAY NG GRADO */
        .excellent { color: #10b981; font-weight: bold; }
        .verygood { color: #3b82f6; font-weight: bold; }
        .good { color: #eab308; font-weight: bold; }
        .fair { color: #f97316; font-weight: bold; }
        .failed { color: #ef4444; font-weight: bold; }
        .remarks-pass { color: #10b981; font-weight: 600; }
        .remarks-fail { color: #ef4444; font-weight: 600; }

        .info-text {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="app-container">

        <!-- ✅ SIDEBAR - VIEW GRADES ANG ACTIVE -->
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
                <li><a href="add_subjects.php">📘 Enrollment</a></li> 
                <li><a href="assessment_form.php">📄 Assessment Form</a></li> 
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php" class="active">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="change_password.php">🔐 Change Password</a></li>
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
                    <h1>📝 View Grades & Academic Record</h1>
                    <p>First Semester, Academic Year 2024 - 2025</p>
                </div>
            </div>

            <!-- ✅ SUMMARY BOXES -->
            <div class="summary-card">
                <div class="summary-box">
                    <div class="summary-label">Total Units</div>
                    <div class="summary-value"><?php echo $total_units; ?></div>
                </div>
                <div class="summary-box">
                    <div class="summary-label">General Average</div>
                    <div class="summary-value"><?php echo $general_average; ?></div>
                </div>
                <div class="summary-box">
                    <div class="summary-label">Status</div>
                    <div class="summary-value" style="color:#10b981">REGULAR</div>
                </div>
            </div>

            <!-- ✅ TABLE OF GRADES -->
            <div class="grades-container">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Description</th>
                            <th>Units</th>
                            <th>Prelim</th>
                            <th>Midterm</th>
                            <th>Semi-Final</th>
                            <th>Final</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($grades as $g): ?>
                        <tr>
                            <td><strong><?php echo $g['code']; ?></strong></td>
                            <td><?php echo $g['subject']; ?></td>
                            <td class="grade-num"><?php echo $g['units']; ?></td>
                            <td class="grade-num <?php 
                                if($g['prelim'] >= 90) echo 'excellent';
                                elseif($g['prelim'] >= 85) echo 'verygood';
                                elseif($g['prelim'] >= 80) echo 'good';
                                elseif($g['prelim'] >= 75) echo 'fair';
                                else echo 'failed';
                            ?>"><?php echo $g['prelim']; ?></td>
                            <td class="grade-num <?php 
                                if($g['midterm'] >= 90) echo 'excellent';
                                elseif($g['midterm'] >= 85) echo 'verygood';
                                elseif($g['midterm'] >= 80) echo 'good';
                                elseif($g['midterm'] >= 75) echo 'fair';
                                else echo 'failed';
                            ?>"><?php echo $g['midterm']; ?></td>
                            <td class="grade-num <?php 
                                if($g['semi'] >= 90) echo 'excellent';
                                elseif($g['semi'] >= 85) echo 'verygood';
                                elseif($g['semi'] >= 80) echo 'good';
                                elseif($g['semi'] >= 75) echo 'fair';
                                else echo 'failed';
                            ?>"><?php echo $g['semi']; ?></td>
                            <td class="grade-num <?php 
                                if($g['final'] >= 90) echo 'excellent';
                                elseif($g['final'] >= 85) echo 'verygood';
                                elseif($g['final'] >= 80) echo 'good';
                                elseif($g['final'] >= 75) echo 'fair';
                                else echo 'failed';
                            ?>"><?php echo $g['final']; ?></td>
                            <td class="grade-num <?php 
                                if($g['final_grade'] >= 90) echo 'excellent';
                                elseif($g['final_grade'] >= 85) echo 'verygood';
                                elseif($g['final_grade'] >= 80) echo 'good';
                                elseif($g['final_grade'] >= 75) echo 'fair';
                                else echo 'failed';
                            ?>"><?php echo $g['final_grade']; ?></td>
                            <td class="<?php echo ($g['remarks'] == 'PASSED') ? 'remarks-pass' : 'remarks-fail'; ?>">
                                <?php echo $g['remarks']; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="info-text">
                    📌 <strong>Note:</strong> Grades are officially recorded and updated by the faculty. For any discrepancies, please contact your instructor or adviser.
                </p>
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