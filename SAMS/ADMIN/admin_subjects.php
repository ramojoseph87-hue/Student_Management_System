<?php
session_start();
include "../STUDENTS/config.php";

// ✅ DEFAULT DATA
if (!isset($_SESSION['subject_data'])) {
    $_SESSION['subject_data'] = [
        [
            'code' => 'IT 101',
            'name' => 'Introduction to Computing',
            'course' => 'BSIS - BS Information Systems',
            'year' => '1st Year',
            'day' => 'Monday / Thursday',
            'start_time' => '07:30 AM',
            'end_time' => '09:00 AM',
            'instructor' => 'Mr. Dela Cruz',
            'room' => 'Lab 1'
        ],
        [
            'code' => 'IT 102',
            'name' => 'Computer Programming',
            'course' => 'BSIS - BS Information Systems',
            'year' => '1st Year',
            'day' => 'Tuesday / Friday',
            'start_time' => '10:30 AM',
            'end_time' => '12:00 PM',
            'instructor' => 'Ms. Santos',
            'room' => 'Lab 2'
        ],
        [
            'code' => 'BA 101',
            'name' => 'Principles of Management',
            'course' => 'BSBA - Business Administration',
            'year' => '1st Year',
            'day' => 'Wednesday',
            'start_time' => '01:00 PM',
            'end_time' => '04:00 PM',
            'instructor' => 'Mr. Reyes',
            'room' => 'Room 5'
        ],
        [
            'code' => 'BEED 101',
            'name' => 'Child & Adolescent Learners',
            'course' => 'BEED - Elementary Education',
            'year' => '1st Year',
            'day' => 'Monday / Wednesday',
            'start_time' => '09:00 AM',
            'end_time' => '10:30 AM',
            'instructor' => 'Ms. Rivera',
            'room' => 'Room 1'
        ],
    ];
}

// ✅ ADD SUBJECT + AYOS PARA HINDI NA DOBLE KAPAG REFRESH
if (isset($_POST['add_subject'])) {
    $_SESSION['subject_data'][] = [
        'code' => strtoupper(trim($_POST['code'])),
        'name' => trim($_POST['name']),
        'course' => $_POST['course'],
        'year' => $_POST['year'],
        'day' => $_POST['day'],
        'start_time' => $_POST['start_time'],
        'end_time' => $_POST['end_time'],
        'instructor' => $_POST['instructor'],
        'room' => trim($_POST['room'])
    ];
    
    // ✅ REDIRECT AGAD PARA MALINIS
    header("Location: admin_subjects.php?success=added");
    exit;
}

// ✅ DELETE SUBJECT
if (isset($_GET['delete'])) {
    $del_code = $_GET['delete'];
    foreach ($_SESSION['subject_data'] as $key => $val) {
        if ($val['code'] == $del_code) {
            unset($_SESSION['subject_data'][$key]);
            $_SESSION['subject_data'] = array_values($_SESSION['subject_data']);
            header("Location: admin_subjects.php?success=deleted");
            exit;
        }
    }
}

// ✅ SEARCH & FILTER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_course = isset($_GET['course_filter']) ? $_GET['course_filter'] : 'All Programs';
$filter_year = isset($_GET['year_filter']) ? $_GET['year_filter'] : 'All Years';

$subjects = $_SESSION['subject_data'];

if ($search != '') {
    $subjects = array_filter($subjects, fn($s) => 
        stripos($s['code'], $search) !== false || 
        stripos($s['name'], $search) !== false ||
        stripos($s['instructor'], $search) !== false
    );
}
if ($filter_course != 'All Programs') {
    $subjects = array_filter($subjects, fn($s) => $s['course'] == $filter_course);
}
if ($filter_year != 'All Years') {
    $subjects = array_filter($subjects, fn($s) => $s['year'] == $filter_year);
}

// ✅ STATISTICS
$total_subjects = count($_SESSION['subject_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects & Schedule | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        /* ✅ BASE DESIGN */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* ✅ ✅ ✅ MODAL - EKSAKTO SA SCREEN MO ✅ ✅ ✅ */
        .modal {
            display: none; /* ✅ IMPORTANTE: DEFAULT NA NAKATAGO */
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.65);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
        }

        .modal-content {
            background-color: #383A54; /* ✅ TUMPAK NA KULAY */
            width: 100%;
            max-width: 400px; /* ✅ SAKTO LANG */
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            color: #FFFFFF;
            position: relative;
            animation: fadeIn 0.25s ease;
            padding: 22px; /* ✅ EKSAKTO SA LAPAD NG PADDING */
            margin: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .modal-header h3 {
            color: #22D185; /* ✅ BERDE NA PAMAGAT */
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .close-btn {
            color: #B0B2C3;
            font-size: 20px;
            font-weight: normal;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #FF6B6B;
        }

        /* ✅ FORM ELEMENTS - PANTAY AT SAKTO */
        .form-group {
            margin-bottom: 14px;
        }

        .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }

        .form-col {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.78rem;
            font-weight: 500;
            color: #E0E0E6;
        }

        .required {
            color: #FF5C5C;
            margin-left: 2px;
        }

        /* ✅ INPUT & DROPDOWN BOX STYLE - PAREHONG-PAREHO */
        input, .select-selected {
            width: 100%;
            padding: 9px 13px;
            background-color: #2E3048; /* ✅ DARKER BOX BACKGROUND */
            border: none;
            border-radius: 4px;
            color: #FFFFFF;
            font-size: 0.8rem;
            height: 40px; /* ✅ EKSAKTO ANG TAAS */
            transition: all 0.2s ease;
        }

        input::placeholder {
            color: #9A9BB2;
        }

        input:focus {
            outline: none;
            border: 1px solid #22D185;
        }

        /* ✅ ✅ ✅ CUSTOM DROPDOWN ✅ ✅ ✅ */
        .select-container {
            position: relative;
            width: 100%;
            z-index: 10;
        }

        .select-container.open {
            z-index: 999999 !important;
        }

        .select-selected {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ✅ ARROW ICON */
        .select-selected::after {
            content: '▼';
            font-size: 0.65rem;
            color: #9A9BB2;
            transition: transform 0.25s ease;
        }

        .select-selected.open {
            border: 1px solid #22D185;
        }

        .select-selected.open::after {
            content: '▲';
            color: #22D185;
        }

        /* ✅ ✅ ✅ OPTIONS LIST ✅ ✅ ✅ */
        .select-options {
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            right: 0;
            background-color: #2E3048;
            border: 1px solid #22D185;
            border-radius: 4px;
            max-height: 160px;
            overflow-y: auto;
            z-index: 999999 !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-3px);
            transition: all 0.2s ease;
        }

        .select-options.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .select-option {
            padding: 9px 13px;
            color: #FFFFFF;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.15s ease;
            border-bottom: 1px solid #383A54;
        }

        .select-option:last-child {
            border-bottom: none;
        }

        /* ✅ ✅ ✅ BERDE HIGHLIGHT ✅ ✅ ✅ */
        .select-option:hover,
        .select-option.selected {
            background-color: #22D185 !important;
            color: #ffffff !important;
        }

        /* ✅ ✅ ✅ SAVE BUTTON - EKSAKTO SA PICTURE ✅ ✅ ✅ */
        .btn-submit {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            background-color: #22D185;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            height: 42px;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit:hover {
            background-color: #14B872;
        }

        /* ✅ TABLE & OTHER STYLES */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .action-btn {
            padding: 10px 18px;
            background: #10B981;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .action-btn:hover { background: #059669; }

        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 12px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid var(--border-color); font-size: 0.85rem; }
        tr:hover { background: rgba(16, 185, 129, 0.05); }

        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; }
        .alert-success { background: #065F46; color: #D1FAE5; border: 1px solid #10B981; }

        .stats-row { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-card { background: var(--card-bg); padding: 15px 20px; border-radius: 10px; flex: 1; min-width: 120px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); }
        .stat-card h3 { font-size: 0.75rem; color: var(--gray); margin-bottom: 5px; text-transform: uppercase; }
        .stat-card .num { font-size: 1.4rem; font-weight: 700; color: #10B981; }

        .filter-bar { background: var(--card-bg); padding: 15px; border-radius: 10px; margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .filter-bar input, .filter-bar select { height: 38px; font-size: 0.8rem; flex: 1; min-width: 150px; }
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
                <p style="color:#BFDBFE; font-weight:bold; font-size:14px;">👨‍💻 Welcome, Administrator!</p>
            </div>
            <ul class="nav-links">
                <li><a href="admindashboard.php">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php" class="active">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php">📂 Requirements</a></li>
                <li><a href="admin_settings.php">⚙️ System Settings</a></li>
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

            <div class="welcome-card">
                <div class="page-header">
                    <div>
                        <h1>📚 Subjects & Schedule Management</h1>
                        <p style="font-size:0.85rem; color:var(--gray); margin-top:5px;">Manage curriculum, subjects, class schedules and instructors</p>
                    </div>
                    <!-- ✅ BUTTON: DITO LANG BUBUKAS -->
                    <button onclick="openModal()" class="action-btn">+ Add New Subject</button>
                </div>
            </div>

            <!-- ✅ SUCCESS MESSAGE -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Record successfully <?= $_GET['success'] == 'added' ? 'added to database!' : 'deleted from database!' ?>
            </div>
            <?php endif; ?>

            <!-- ✅ STATISTICS -->
            <div class="stats-row">
                <div class="stat-card"><h3>Total Subjects</h3><div class="num"><?= $total_subjects ?></div></div>
                <div class="stat-card"><h3>For BSIS</h3><div class="num"><?= count(array_filter($_SESSION['subject_data'], fn($s) => str_contains($s['course'], 'BSIS'))) ?></div></div>
                <div class="stat-card"><h3>For BSBA</h3><div class="num"><?= count(array_filter($_SESSION['subject_data'], fn($s) => str_contains($s['course'], 'BSBA'))) ?></div></div>
                <div class="stat-card"><h3>Others</h3><div class="num"><?= count(array_filter($_SESSION['subject_data'], fn($s) => !str_contains($s['course'], 'BSIS') && !str_contains($s['course'], 'BSBA'))) ?></div></div>
            </div>

            <!-- ✅ SEARCH & FILTER -->
            <div class="filter-bar">
                <form method="GET" style="display: contents; width:100%;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search Subject Code, Name or Instructor...">
                    
                    <select name="course_filter" onchange="this.form.submit()">
                        <option>All Programs</option>
                        <option <?= $filter_course=='BEED - Elementary Education'?'selected':'' ?>>BEED - Elementary Education</option>
                        <option <?= $filter_course=='BSOA - Business Office Administration'?'selected':'' ?>>BSOA - Business Office Administration</option>
                        <option <?= $filter_course=='BSBA - Business Administration'?'selected':'' ?>>BSBA - Business Administration</option>
                        <option <?= $filter_course=='BSIS - BS Information Systems'?'selected':'' ?>>BSIS - BS Information Systems</option>
                        <option <?= $filter_course=='BSCRIM - Criminology'?'selected':'' ?>>BSCRIM - Criminology</option>
                    </select>

                    <select name="year_filter" onchange="this.form.submit()">
                        <option>All Years</option>
                        <option <?= $filter_year=='1st Year'?'selected':'' ?>>1st Year</option>
                        <option <?= $filter_year=='2nd Year'?'selected':'' ?>>2nd Year</option>
                        <option <?= $filter_year=='3rd Year'?'selected':'' ?>>3rd Year</option>
                        <option <?= $filter_year=='4th Year'?'selected':'' ?>>4th Year</option>
                    </select>

                    <button type="submit" class="action-btn" style="height:38px; padding:0 15px;">🔍 Go</button>
                    <?php if ($search != '' || $filter_course != 'All Programs' || $filter_year != 'All Years'): ?>
                    <a href="admin_subjects.php" class="action-btn" style="background:#EF4444; text-decoration:none; height:38px; padding:0 15px; display:flex; align-items:center; justify-content:center;">❌ Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- ✅ SUBJECTS TABLE -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th>Course / Year</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Instructor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($subjects) == 0): ?>
                            <tr><td colspan="7" style="text-align:center; color:var(--gray); padding:30px;">😕 No subjects found matching your criteria.</td></tr>
                        <?php else: ?>
                        <?php foreach ($subjects as $s): ?>
                        <tr>
                            <td><strong style="color:#10B981"><?= $s['code'] ?></strong></td>
                            <td><strong><?= $s['name'] ?></strong></td>
                            <td>
                                <?= $s['course'] ?><br>
                                <span style="font-size:0.7rem; color:var(--gray)"><?= $s['year'] ?></span>
                            </td>
                            <td>
                                <span style="background:#3B82F6; color:white; padding:2px 6px; border-radius:4px; font-size:0.7rem;"><?= $s['day'] ?></span><br>
                                <span style="font-size:0.7rem; color:#EF4444; font-weight:500;"><?= $s['start_time'] ?> - <?= $s['end_time'] ?></span>
                            </td>
                            <td><?= $s['room'] ?></td>
                            <td><?= $s['instructor'] ?></td>
                            <td>
                                <div style="display:flex; gap:4px;">
                                    <button style="background:#3B82F6; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; cursor:pointer;">👁️</button>
                                    <button style="background:#F59E0B; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; cursor:pointer;">✏️</button>
                                    <a href="admin_subjects.php?delete=<?= $s['code'] ?>" onclick="return confirm('⚠️ Delete this subject?')">
                                        <button style="background:#EF4444; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; cursor:pointer;">🗑️</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ✅ ✅ ✅ MODAL: REGISTER NEW SUBJECT - EKSAKTO SA SCREEN MO ✅ ✅ ✅ -->
    <div id="addModal" class="modal" style="display: none;"> <!-- ✅ SURE NA NAKATAGO -->
        <div class="modal-content">
            <div class="modal-header">
                <h3>📚 Register New Subject & Schedule</h3>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>

            <form method="POST" id="subjectForm">
                <!-- Row 1: Code & Year -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Subject Code <span class="required">*</span></label>
                        <input type="text" name="code" placeholder="e.g. IT 101" required>
                    </div>
                    <div class="form-col">
                        <label>Year Level <span class="required">*</span></label>
                        <div class="select-container" id="year_select">
                            <div class="select-selected">-- Select Year --</div>
                            <div class="select-options">
                                <div class="select-option" data-value="1st Year">1st Year</div>
                                <div class="select-option" data-value="2nd Year">2nd Year</div>
                                <div class="select-option" data-value="3rd Year">3rd Year</div>
                                <div class="select-option" data-value="4th Year">4th Year</div>
                            </div>
                            <input type="hidden" name="year" required>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Subject Name -->
                <div class="form-group">
                    <label>Subject Name / Description <span class="required">*</span></label>
                    <input type="text" name="name" placeholder="e.g. Introduction to Computing" required>
                </div>

                <!-- Row 3: Course -->
                <div class="form-group">
                    <label>Course / Program <span class="required">*</span></label>
                    <div class="select-container" id="course_select">
                        <div class="select-selected">-- Select Course --</div>
                        <div class="select-options">
                            <div class="select-option" data-value="BEED - Elementary Education">BEED - Elementary Education</div>
                            <div class="select-option" data-value="BSOA - Business Office Administration">BSOA - Business Office Administration</div>
                            <div class="select-option" data-value="BSBA - Business Administration">BSBA - Business Administration</div>
                            <div class="select-option" data-value="BSIS - BS Information Systems">BSIS - BS Information Systems</div>
                            <div class="select-option" data-value="BSCRIM - Criminology">BSCRIM - Criminology</div>
                        </div>
                        <input type="hidden" name="course" required>
                    </div>
                </div>

                <!-- Row 4: Schedule Day -->
                <div class="form-group">
                    <label>Schedule Day <span class="required">*</span></label>
                    <input type="text" name="day" placeholder="e.g. Mon / Wed / Fri" required>
                </div>

                <!-- Row 5: Start & End Time -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Start Time <span class="required">*</span></label>
                        <div class="select-container" id="start_select">
                            <div class="select-selected">-- Select Time --</div>
                            <div class="select-options">
                                <div class="select-option" data-value="07:00 AM">07:00 AM</div>
                                <div class="select-option" data-value="07:30 AM">07:30 AM</div>
                                <div class="select-option" data-value="08:00 AM">08:00 AM</div>
                                <div class="select-option" data-value="08:30 AM">08:30 AM</div>
                                <div class="select-option" data-value="09:00 AM">09:00 AM</div>
                                <div class="select-option" data-value="09:30 AM">09:30 AM</div>
                                <div class="select-option" data-value="10:00 AM">10:00 AM</div>
                                <div class="select-option" data-value="10:30 AM">10:30 AM</div>
                                <div class="select-option" data-value="11:00 AM">11:00 AM</div>
                                <div class="select-option" data-value="11:30 AM">11:30 AM</div>
                                <div class="select-option" data-value="12:00 PM">12:00 PM</div>
                                <div class="select-option" data-value="12:30 PM">12:30 PM</div>
                                <div class="select-option" data-value="01:00 PM">01:00 PM</div>
                                <div class="select-option" data-value="01:30 PM">01:30 PM</div>
                                <div class="select-option" data-value="02:00 PM">02:00 PM</div>
                                <div class="select-option" data-value="02:30 PM">02:30 PM</div>
                                <div class="select-option" data-value="03:00 PM">03:00 PM</div>
                                <div class="select-option" data-value="03:30 PM">03:30 PM</div>
                                <div class="select-option" data-value="04:00 PM">04:00 PM</div>
                                <div class="select-option" data-value="04:30 PM">04:30 PM</div>
                                <div class="select-option" data-value="05:00 PM">05:00 PM</div>
                                <div class="select-option" data-value="05:30 PM">05:30 PM</div>
                                <div class="select-option" data-value="06:00 PM">06:00 PM</div>
                                <div class="select-option" data-value="06:30 PM">06:30 PM</div>
                                <div class="select-option" data-value="07:00 PM">07:00 PM</div>
                                <div class="select-option" data-value="07:30 PM">07:30 PM</div>
                                <div class="select-option" data-value="08:00 PM">08:00 PM</div>
                            </div>
                            <input type="hidden" name="start_time" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <label>End Time <span class="required">*</span></label>
                        <div class="select-container" id="end_select">
                            <div class="select-selected">-- Select Time --</div>
                            <div class="select-options">
                                <div class="select-option" data-value="07:00 AM">07:00 AM</div>
                                <div class="select-option" data-value="07:30 AM">07:30 AM</div>
                                <div class="select-option" data-value="08:00 AM">08:00 AM</div>
                                <div class="select-option" data-value="08:30 AM">08:30 AM</div>
                                <div class="select-option" data-value="09:00 AM">09:00 AM</div>
                                <div class="select-option" data-value="09:30 AM">09:30 AM</div>
                                <div class="select-option" data-value="10:00 AM">10:00 AM</div>
                                <div class="select-option" data-value="10:30 AM">10:30 AM</div>
                                <div class="select-option" data-value="11:00 AM">11:00 AM</div>
                                <div class="select-option" data-value="11:30 AM">11:30 AM</div>
                                <div class="select-option" data-value="12:00 PM">12:00 PM</div>
                                <div class="select-option" data-value="12:30 PM">12:30 PM</div>
                                <div class="select-option" data-value="01:00 PM">01:00 PM</div>
                                <div class="select-option" data-value="01:30 PM">01:30 PM</div>
                                <div class="select-option" data-value="02:00 PM">02:00 PM</div>
                                <div class="select-option" data-value="02:30 PM">02:30 PM</div>
                                <div class="select-option" data-value="03:00 PM">03:00 PM</div>
                                <div class="select-option" data-value="03:30 PM">03:30 PM</div>
                                <div class="select-option" data-value="04:00 PM">04:00 PM</div>
                                <div class="select-option" data-value="04:30 PM">04:30 PM</div>
                                <div class="select-option" data-value="05:00 PM">05:00 PM</div>
                                <div class="select-option" data-value="05:30 PM">05:30 PM</div>
                                <div class="select-option" data-value="06:00 PM">06:00 PM</div>
                                <div class="select-option" data-value="06:30 PM">06:30 PM</div>
                                <div class="select-option" data-value="07:00 PM">07:00 PM</div>
                                <div class="select-option" data-value="07:30 PM">07:30 PM</div>
                                <div class="select-option" data-value="08:00 PM">08:00 PM</div>
                            </div>
                            <input type="hidden" name="end_time" required>
                        </div>
                    </div>
                </div>

                <!-- Row 6: Room & Instructor -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Room / Section <span class="required">*</span></label>
                        <input type="text" name="room" placeholder="e.g. Lab 1 or Room 5" required>
                    </div>
                    <div class="form-col">
                        <label>Instructor <span class="required">*</span></label>
                        <div class="select-container" id="instructor_select">
                            <div class="select-selected">-- Select Instructor --</div>
                            <div class="select-options">
                                <div class="select-option" data-value="Mr. Dela Cruz">Mr. Dela Cruz</div>
                                <div class="select-option" data-value="Ms. Santos">Ms. Santos</div>
                                <div class="select-option" data-value="Mr. Reyes">Mr. Reyes</div>
                                <div class="select-option" data-value="Ms. Rivera">Ms. Rivera</div>
                                <div class="select-option" data-value="Capt. Lopez">Capt. Lopez</div>
                                <div class="select-option" data-value="Mr. Santos">Mr. Santos</div>
                                <div class="select-option" data-value="Ms. Garcia">Ms. Garcia</div>
                                <div class="select-option" data-value="Mr. Fernandez">Mr. Fernandez</div>
                                <div class="select-option" data-value="Ms. Torres">Ms. Torres</div>
                                <div class="select-option" data-value="Others">Others</div>
                            </div>
                            <input type="hidden" name="instructor" required>
                        </div>
                    </div>
                </div>

                <!-- ✅ ✅ ✅ SAVE BUTTON - EKSAKTO SA SCREEN ✅ ✅ ✅ -->
                <button type="submit" name="add_subject" class="btn-submit">✅ Save Subject & Schedule</button>
            </form>
        </div>
    </div>

    <script src="../admin_script.js"></script>
    <script>
        // ✅ MODAL CONTROL - SIGURADONG NAKATAGO SA SIMULA
        function openModal() {
            document.getElementById('addModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // ✅ CLOSE WHEN CLICK OUTSIDE
        window.onclick = function(e) {
            const modal = document.getElementById('addModal');
            if (e.target === modal) closeModal();
        };

        // ✅ ✅ ✅ DROPDOWN SCRIPT ✅ ✅ ✅
        document.addEventListener('DOMContentLoaded', function() {
            const allContainers = document.querySelectorAll('.select-container');

            allContainers.forEach(container => {
                const selectedBox = container.querySelector('.select-selected');
                const optionsBox = container.querySelector('.select-options');
                const options = container.querySelectorAll('.select-option');
                const hiddenInput = container.querySelector('input[type="hidden"]');

                // ✅ BUKAS / SARA
                selectedBox.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // ✅ ISARA LAHAT NG IBA
                    allContainers.forEach(c => {
                        if(c !== container) {
                            c.classList.remove('open');
                            c.querySelector('.select-selected').classList.remove('open');
                            c.querySelector('.select-options').classList.remove('open');
                        }
                    });

                    // ✅ BUKSAN ANG ITO
                    container.classList.toggle('open');
                    selectedBox.classList.toggle('open');
                    optionsBox.classList.toggle('open');
                });

                // ✅ PILIHAN
                options.forEach(option => {
                    option.addEventListener('click', function() {
                        const val = this.getAttribute('data-value');
                        const txt = this.textContent;

                        selectedBox.textContent = txt;
                        hiddenInput.value = val;

                        // ✅ MARK AS SELECTED
                        options.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');

                        // ✅ ISARA PAGKAPILI
                        container.classList.remove('open');
                        selectedBox.classList.remove('open');
                        optionsBox.classList.remove('open');
                    });
                });
            });

            // ✅ ISARA KAPAG KUMA CLICK SA LABAS
            document.addEventListener('click', function() {
                allContainers.forEach(container => {
                    container.classList.remove('open');
                    container.querySelector('.select-selected').classList.remove('open');
                    container.querySelector('.select-options').classList.remove('open');
                });
            });
        });
    </script>
</body>
</html>