<?php
session_start();

// ✅ KUNIN ANG MGA NAPILING SUBJECT AT DETALYE
$all_subjects = [
    1 => ['id'=>1, 'code'=>'IT101', 'name'=>'Introduction to Computing', 'units'=>3, 'sem'=>'1st Semester'],
    2 => ['id'=>2, 'code'=>'CS101', 'name'=>'Computer Programming 1', 'units'=>3, 'sem'=>'1st Semester'],
    3 => ['id'=>3, 'code'=>'GE101', 'name'=>'Understanding the Self', 'units'=>3, 'sem'=>'1st Semester'],
    4 => ['id'=>4, 'code'=>'MATH101', 'name'=>'College Algebra', 'units'=>3, 'sem'=>'1st Semester'],
    5 => ['id'=>5, 'code'=>'IT102', 'name'=>'Data Structures and Algorithms', 'units'=>3, 'sem'=>'2nd Semester'],
    6 => ['id'=>6, 'code'=>'GE102', 'name'=>'Readings in Philippine History', 'units'=>3, 'sem'=>'2nd Semester'],
    7 => ['id'=>7, 'code'=>'IT103', 'name'=>'Computer Networks', 'units'=>3, 'sem'=>'2nd Semester'],
];

$enrolled_subjects = [];
$total_units = 0;
$total_tuition = 0;
$fee_per_unit = 450; // ✅ Presyo bawat unit (pwede mong palitan)

if(!empty($_SESSION['enrolled_ids'])){
    foreach($_SESSION['enrolled_ids'] as $id){
        if(isset($all_subjects[$id])){
            $enrolled_subjects[] = $all_subjects[$id];
            $total_units += $all_subjects[$id]['units'];
            $total_tuition += ($all_subjects[$id]['units'] * $fee_per_unit);
        }
    }
}

// ✅ IBA PANG BAYARIN
$misc_fee = 1250;  // Library, ID, Athletics, etc.
$lab_fee = 800;    // Kung may computer subject
$total_amount = $total_tuition + $misc_fee + $lab_fee;

// ✅ KUNG WALANG SUBJECT NA NAPILI
if(empty($enrolled_subjects)){
    echo "<script>alert('⚠️ Please select subjects first!'); window.location.href='add_subjects.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Form | SAMS</title>
    <link rel="stylesheet" href="../style1.css?v=13"> 
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        .assessment-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .assessment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        .assessment-header h2 {
            color: var(--primary-light);
            font-size: 1.2rem;
            font-weight: 700;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 0.9rem;
        }
        .info-label { color: var(--gray); }
        .info-value { color: var(--text-color); font-weight: 500; }

        .subject-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }
        .subject-table th {
            background: #1e293b;
            color: #93c5fd;
            padding: 10px;
            text-align: left;
            font-size: 0.85rem;
            border-bottom: 1px solid #334155;
        }
        .subject-table td {
            padding: 10px;
            border-bottom: 1px solid #334155;
            font-size: 0.85rem;
            color: var(--text-color);
        }
        .subject-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.9rem;
        }
        .total-final {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #86efac;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin: 16px 0;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        .btn-back {
            flex: 1;
            padding: 12px;
            background: #475569;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .btn-back:hover { background: #334155; }

        .btn-pay {
            flex: 1;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .btn-pay:hover { background: #1d4ed8; }

        .btn-print {
            padding: 12px 16px;
            background: #f59e0b;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-print:hover { background: #d97706; }

        @media print {
            .sidebar, .mode-switch, .btn-group { display: none !important; }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            body { background: white !important; color: black !important; }
            .assessment-card { border: 1px solid #000 !important; box-shadow: none !important; }
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
                <li><a href="Profile.php">👤 My Profile</a></li>
                <li><a href="add_subjects.php">📘 Enrollment</a></li> 
                <li><a href="assessment_form.php" class="active">📄 Assessment Form</a></li> 
                <li><a href="classsched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php">📝 View Grades</a></li>
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
                    <input type="checkbox" id="darkmode" checked>
                    <span class="slider"></span>
                </label>
                <span>🌙</span>
            </div>

            <!-- ✅ HEADER -->
            <div class="welcome-card">
                <h1>📄 Assessment Form</h1>
                <p>Academic Year 2025-2026 | BS Information Systems - 1st Year</p>
            </div>

            <!-- ✅ ASSESSMENT CONTENT -->
            <div class="assessment-card">
                <div class="assessment-header">
                    <h2>Official Assessment of Fees</h2>
                    <button class="btn-print" onclick="window.print()">🖨️ Print Form</button>
                </div>

                <!-- ✅ STUDENT INFO -->
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value"><?php echo $_SESSION['fullname'] ?? 'Joseph Louis Brua Ramo'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Student ID:</span>
                    <span class="info-value">2023-00145</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Semester:</span>
                    <span class="info-value"><?php echo $_SESSION['selected_sem'] ?? '1st Semester'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date Issued:</span>
                    <span class="info-value"><?php echo date('F d, Y'); ?></span>
                </div>

                <!-- ✅ SUBJECTS LIST -->
                <table class="subject-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Description</th>
                            <th>Units</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($enrolled_subjects as $sub): ?>
                        <tr>
                            <td><?php echo $sub['code']; ?></td>
                            <td><?php echo $sub['name']; ?></td>
                            <td><?php echo $sub['units']; ?></td>
                            <td>₱ <?php echo number_format($sub['units'] * $fee_per_unit, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- ✅ FEES BREAKDOWN -->
                <div style="margin-left: auto; width: 350px;">
                    <div class="fee-row">
                        <span>Tuition Fee (<?php echo $total_units; ?> units)</span>
                        <span>₱ <?php echo number_format($total_tuition, 2); ?></span>
                    </div>
                    <div class="fee-row">
                        <span>Miscellaneous Fee</span>
                        <span>₱ <?php echo number_format($misc_fee, 2); ?></span>
                    </div>
                    <div class="fee-row">
                        <span>Laboratory Fee</span>
                        <span>₱ <?php echo number_format($lab_fee, 2); ?></span>
                    </div>
                    <hr style="border: 0.5px solid #334155; margin: 8px 0;">
                    
                    <div class="total-final">
                        <span>TOTAL AMOUNT TO PAY</span>
                        <span>₱ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>

                <!-- ✅ BUTTONS -->
                <div class="btn-group">
                    <a href="add_subjects.php" class="btn-back">⬅️ Back to Enrollment</a>
                    <a href="Payment.php" class="btn-pay">💳 PROCEED TO PAYMENT</a>
                </div>
            </div>

        </div>
    </div>

    <script src="../student.js"></script>
</body>
</html>