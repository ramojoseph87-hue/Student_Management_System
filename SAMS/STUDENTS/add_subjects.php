<?php
session_start();

// ✅ I-SAVE ANG NAPILING SEM PARA HINDI MAWALA
if(isset($_POST['sem']) && $_POST['sem'] != ""){
    $_SESSION['selected_sem'] = $_POST['sem'];
}
$selected_semester = isset($_SESSION['selected_sem']) ? $_SESSION['selected_sem'] : "";

// ✅ SIGURADUHIN MAY LAMAN ANG SESSION NG MGA NAPILI
if(!isset($_SESSION['enrolled_ids'])) {
    $_SESSION['enrolled_ids'] = [];
}

// ✅ PROSESO: PAG DAGDAG NG SUBJECT
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subject_id'])){
    $add_id = $_POST['add_subject_id'];
    if(!in_array($add_id, $_SESSION['enrolled_ids'])) {
        $_SESSION['enrolled_ids'][] = $add_id;
        $msg = "✅ Subject successfully added!";
        $msg_type = "success";
    } else {
        $msg = "⚠️ Subject is already in your list.";
        $msg_type = "warning";
    }
}

// ✅ PROSESO: PAG BURA NG SUBJECT
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id'])){
    $remove_id = $_POST['remove_id'];
    $new_list = [];
    foreach($_SESSION['enrolled_ids'] as $id){
        if($id != $remove_id) $new_list[] = $id;
    }
    $_SESSION['enrolled_ids'] = $new_list;
    $msg = "❌ Subject removed from list.";
    $msg_type = "danger";
}

// ✅ PROSESO: BURA LAHAT
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_all'])){
    $_SESSION['enrolled_ids'] = [];
    $msg = "🗑️ All subjects cleared! Start again.";
    $msg_type = "danger";
}

// ✅ PROSESO: PAG PALIT NG SEM = BURAHIN ANG LISTAHAN
if(isset($_POST['change_sem'])){
    $_SESSION['enrolled_ids'] = []; 
    $msg = "🔄 Semester changed. List reset.";
    $msg_type = "warning";
}

// ==================================================
// ✅ SAMPLE DATA / SUBJECT LIST
// ==================================================
$all_subjects = [
    1 => ['id'=>1, 'code'=>'IT101', 'name'=>'Introduction to Computing', 'units'=>3, 'sem'=>'1st Semester'],
    2 => ['id'=>2, 'code'=>'CS101', 'name'=>'Computer Programming 1', 'units'=>3, 'sem'=>'1st Semester'],
    3 => ['id'=>3, 'code'=>'GE101', 'name'=>'Understanding the Self', 'units'=>3, 'sem'=>'1st Semester'],
    4 => ['id'=>4, 'code'=>'MATH101', 'name'=>'College Algebra', 'units'=>3, 'sem'=>'1st Semester'],
    5 => ['id'=>5, 'code'=>'IT102', 'name'=>'Data Structures and Algorithms', 'units'=>3, 'sem'=>'2nd Semester'],
    6 => ['id'=>6, 'code'=>'GE102', 'name'=>'Readings in Philippine History', 'units'=>3, 'sem'=>'2nd Semester'],
    7 => ['id'=>7, 'code'=>'IT103', 'name'=>'Computer Networks', 'units'=>3, 'sem'=>'2nd Semester'],
];

// ✅ KUNIN ANG SUBJECTS BASE SA NAPILING SEM
$subjects_list = [];
if($selected_semester != ""){
    foreach($all_subjects as $s){
        if($s['sem'] == $selected_semester) $subjects_list[] = $s;
    }
}

// ✅ COMPUTE KABUUAN NG UNITS
$enrolled_subjects = [];
$total_units = 0;

if(!empty($_SESSION['enrolled_ids'])){
    foreach($_SESSION['enrolled_ids'] as $id){
        if(isset($all_subjects[$id])){
            $enrolled_subjects[] = $all_subjects[$id];
            $total_units += $all_subjects[$id]['units'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Enrollment | SAMS</title>
    <link rel="stylesheet" href="../style1.css?v=13"> 
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ DITO KO LANG INILAGAY ANG MGA STYLE NA PARA LANG SA PAGE NA ITO. 
           ANG MGA PANGKALAHATANG STYLE NASA style1.css NA */

        /* ✅ STEP GUIDE DESIGN */
        .step-guide { 
            display: flex; 
            justify-content: space-between; 
            text-align: center; 
            margin-bottom: 16px;
            background: var(--card-bg); 
            padding: 12px; 
            border-radius: 10px; 
            border: 1px solid var(--border-color); 
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .step { 
            flex: 1; 
            padding: 10px 6px; 
            color: var(--gray); 
            font-size: 0.78rem; 
            font-weight: 600; 
            border-radius: 6px; 
            transition: all 0.3s ease;
        }
        .step.active { 
            color: var(--primary-light); 
            background: rgba(59, 130, 246, 0.12); 
            border: 1px solid rgba(59, 130, 246, 0.2); 
        }
        .step.done { 
            color: var(--success); 
            background: rgba(16, 185, 129, 0.12); 
            border: 1px solid rgba(16, 185, 129, 0.2); 
        }

        /* ✅ GRID LAYOUT: 2 KOLUMNA */
        .container-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 16px; 
            height: calc(100vh - 160px); 
        }

        .card-box { 
            background-color: var(--card-bg); 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid var(--border-color); 
            display: flex; 
            flex-direction: column;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .card-box h3 { 
            color: var(--text-color); 
            margin-bottom: 16px; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            font-size: 1rem; 
            font-weight: 600;
            opacity: 0.9;
        }

        /* ✅ ALERT / MESSAGE BOX */
        .alert { 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 16px; 
            font-weight: 500; 
            text-align: center; 
            font-size: 0.85rem; 
            border-width: 1px; 
            border-style: solid; 
        }
        .alert.success { background: rgba(16, 185, 129, 0.08); color: #86efac; border-color: rgba(16, 185, 129, 0.2); }
        .alert.warning { background: rgba(245, 158, 11, 0.08); color: #fdba74; border-color: rgba(245, 158, 11, 0.2); }
        .alert.danger { background: rgba(239, 68, 68, 0.08); color: #fca5a5; border-color: rgba(239, 68, 68, 0.2); }

        /* ✅ DROPDOWN / SELECT BOX */
        .sem-selector { 
            width: 100%; 
            padding: 12px 16px; 
            border-radius: 8px; 
            border: 2px solid var(--border-color); 
            background: var(--bg-color); 
            color: var(--text-color); 
            font-size: 0.9rem; 
            font-weight: 500;
            margin-bottom: 16px; 
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .sem-selector:focus, .sem-selector:hover { border-color: var(--primary-light); outline: none; }

        /* ✅ TABLE STYLE */
        .table-container { 
            flex: 1; 
            overflow-y: auto; 
            border-radius: 8px; 
            border: 1px solid var(--border-color); 
            background-color: var(--bg-color);
            margin-bottom: 12px;
        }
        .subject-table { width: 100%; border-collapse: collapse; }
        .subject-table th, .subject-table td { 
            padding: 10px 12px; 
            text-align: left; 
            border-bottom: 1px solid var(--border-color); 
            font-size: 0.82rem; 
        }
        .subject-table th { 
            background: var(--card-bg); 
            color: var(--primary-light); 
            font-weight: 600; 
            position: sticky; 
            top: 0; 
            z-index: 10;
        }
        .subject-table tr:hover { background: rgba(59, 130, 246, 0.05); }

        /* ✅ BUTTONS */
        .btn-add { 
            background: var(--success); 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: 500; 
            font-size: 0.78rem; 
            transition: transform 0.2s ease;
        }
        .btn-add:hover { background: #059669; transform: scale(0.95); }
        
        .btn-remove { 
            background: var(--danger); 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: 500; 
            font-size: 0.78rem; 
            transition: transform 0.2s ease;
        }
        .btn-remove:hover { background: #dc2626; transform: scale(0.95); }

        /* ✅ SUMMARY BOX */
        .summary-box { 
            background: var(--bg-color); 
            border: 1px solid var(--border-color); 
            border-radius: 10px; 
            padding: 14px; 
            margin: 12px 0; 
            text-align: center; 
        }
        .summary-box .total-units { font-size: 1.2rem; font-weight: bold; color: var(--primary-light); }
        .summary-box .label { font-size: 0.8rem; color: var(--gray); margin-bottom: 4px; }

        /* ✅ MAIN BUTTONS */
        .btn-primary { 
            flex: 1;
            padding: 12px; 
            background: var(--primary-light); 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 0.9rem; 
            font-weight: bold; 
            cursor: pointer; 
            text-decoration: none;
            text-align: center;
            display: block;
            transition: background 0.3s ease;
        }
        .btn-primary:hover { background: #1d4ed8; color: white; }
        
        .btn-secondary { 
            padding: 10px 14px; 
            background: var(--warning); 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: 500; 
            font-size: 0.82rem; 
            transition: background 0.3s ease;
        }
        .btn-secondary:hover { background: #d97706; }

        .empty-text { padding: 30px 0; text-align: center; color: var(--gray); font-style: italic; font-size: 0.85rem; }

        /* ✅ BUTTON GROUP */
        .button-group { display: flex; gap: 12px; margin-top: auto; padding-top: 12px; }

        /* ✅ RESPONSIVE PARA SA TABLET AT CP */
        @media (max-width: 1024px) {
            .container-grid { grid-template-columns: 1fr; height: auto; gap: 12px; }
            .card-box { height: 450px; }
        }
        @media (max-width: 768px) {
            .step-guide { flex-wrap: wrap; }
            .step { flex: auto; min-width: 80px; }
        }
    </style>
</head>
<body>

    <div class="app-container">

        <!-- ✅ SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="School Logo">
                <h2>SAMS</h2>
                <p>Saint Thomas Aquinas College</p>
                <hr>
                <p>Welcome, <strong><?php echo $_SESSION['fullname'] ?? 'Student'; ?></strong>!</p>
            </div>
            <ul class="nav-links">
                <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
                <li><a href="Profile.php">👤 My Profile</a></li>
                <li><a href="add_subjects.php" class="active">📘 Enrollment</a></li> 
                <li><a href="assessment_form.php">📄 Assessment Form</a></li> 
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
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
                    <input type="checkbox" id="darkmode">
                    <span class="slider"></span>
                </label>
                <span>🌙</span>
            </div>

            <!-- ✅ HEADER -->
            <div class="welcome-card">
                <h1>📘 Subject Enrollment</h1>
                <p>Academic Year 2025-2026 | BS Information Systems - 1st Year</p>
            </div>

            <!-- ✅ STEP GUIDE -->
            <div class="step-guide">
                <div class="step <?php echo ($selected_semester != "") ? 'done' : 'active'; ?>">1. Select Semester</div>
                <div class="step <?php echo ($total_units > 0) ? 'done' : (($selected_semester != "") ? 'active' : ''); ?>">2. Add Subjects</div>
                <div class="step">3. Review List</div>
                <div class="step">4. Proceed to Payment</div>
            </div>

            <?php if(isset($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>


            <div class="container-grid">

                <!-- ✅ KALIWA: AVAILABLE SUBJECTS -->
                <div class="card-box">
                    <h3>📋 Available Subjects</h3>

                    <form method="POST" action="add_subjects.php">
                        <select name="sem" class="sem-selector" onchange="this.form.submit();">
                            <option value="">-- CHOOSE SEMESTER --</option>
                            <option value="1st Semester" <?php if($selected_semester == "1st Semester") echo "selected"; ?>>✅ 1ST SEMESTER</option>
                            <option value="2nd Semester" <?php if($selected_semester == "2nd Semester") echo "selected"; ?>>✅ 2ND SEMESTER</option>
                        </select>
                        <input type="hidden" name="change_sem" value="1">
                    </form>

                    <div class="table-container">
                        <table class="subject-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($selected_semester != "" && !empty($subjects_list)): ?>
                                    <?php foreach($subjects_list as $sub): ?>
                                    <tr>
                                        <td><?php echo $sub['code']; ?></td>
                                        <td><?php echo $sub['name']; ?></td>
                                        <td><?php echo $sub['units']; ?></td>
                                        <td>
                                            <form method="POST" action="add_subjects.php" style="display:inline">
                                                <input type="hidden" name="add_subject_id" value="<?php echo $sub['id']; ?>">
                                                <button type="submit" class="btn-add">➕ Add</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="empty-text">👆 Please select semester first</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- ✅ KANAN: MY SUBJECTS SUMMARY -->
                <div class="card-box">
                    <h3>📚 My Selected Subjects</h3>

                    <div class="table-container" style="max-height: 300px;">
                        <table class="subject-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($enrolled_subjects)): ?>
                                    <?php foreach($enrolled_subjects as $enr): ?>
                                    <tr>
                                        <td><?php echo $enr['code']; ?></td>
                                        <td><?php echo $enr['name']; ?></td>
                                        <td><?php echo $enr['units']; ?></td>
                                        <td>
                                            <form method="POST" action="add_subjects.php" style="display:inline">
                                                <input type="hidden" name="remove_id" value="<?php echo $enr['id']; ?>">
                                                <button type="submit" class="btn-remove">❌ Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="empty-text">📭 No subjects added yet</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- ✅ TOTAL UNITS -->
                    <div class="summary-box">
                        <div class="label">TOTAL UNITS ENROLLED</div>
                        <div class="total-units"><?php echo $total_units; ?></div>
                    </div>

                    <!-- ✅ BUTTONS -->
                    <div class="button-group">
                        <a href="assessment_form.php" class="btn-primary">📄 PROCEED TO ASSESSMENT</a>
                        <form method="POST" action="add_subjects.php" onsubmit="return confirm('Clear all subjects?');">
                            <button type="submit" name="clear_all" class="btn-secondary">🗑️ Clear All</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="../student.js"></script>
</body>
</html>