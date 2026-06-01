<?php
session_start();
include "../STUDENTS/config.php";

// ✅ SAMPLE GRADE DATA - UPDATED NA MAY PRE-MID & PRE-FINAL
if (!isset($_SESSION['grade_data'])) {
    $_SESSION['grade_data'] = [
        [
            'student_id' => '2023-00125',
            'student_name' => 'Juan Dela Cruz',
            'course' => 'BS Information Systems',
            'year_level' => '2nd Year',
            'semester' => '2nd Semester',
            'subject_code' => 'IT 201',
            'subject_name' => 'Data Structures and Algorithms',
            'instructor' => 'Mr. Mark Reyes',
            'pre_mid' => 88,
            'midterm' => 92,
            'pre_final' => 93,
            'final' => 94,
            'final_grade' => 91.75,
            'remarks' => 'Passed',
            'date_encoded' => '2026-05-15'
        ],
        [
            'student_id' => '2023-00126',
            'student_name' => 'Maria Santos',
            'course' => 'BS Information Systems',
            'year_level' => '2nd Year',
            'semester' => '2nd Semester',
            'subject_code' => 'IT 201',
            'subject_name' => 'Data Structures and Algorithms',
            'instructor' => 'Mr. Mark Reyes',
            'pre_mid' => 82,
            'midterm' => 85,
            'pre_final' => 87,
            'final' => 88,
            'final_grade' => 85.50,
            'remarks' => 'Passed',
            'date_encoded' => '2026-05-15'
        ],
        [
            'student_id' => '2023-00127',
            'student_name' => 'Jose Rizal',
            'course' => 'BS Business Administration',
            'year_level' => '2nd Year',
            'semester' => '2nd Semester',
            'subject_code' => 'BA 202',
            'subject_name' => 'Financial Management',
            'instructor' => 'Ms. Anna Garcia',
            'pre_mid' => 76,
            'midterm' => 78,
            'pre_final' => 74,
            'final' => 75,
            'final_grade' => 75.75,
            'remarks' => 'Conditional',
            'date_encoded' => '2026-05-16'
        ],
        [
            'student_id' => '2023-00128',
            'student_name' => 'Andres Bonifacio',
            'course' => 'BS Criminology',
            'year_level' => '3rd Year',
            'semester' => '2nd Semester',
            'subject_code' => 'CRIM 305',
            'subject_name' => 'Criminal Law Book 2',
            'instructor' => 'Capt. John Doe',
            'pre_mid' => 62,
            'midterm' => 65,
            'pre_final' => 68,
            'final' => 70,
            'final_grade' => 66.25,
            'remarks' => 'Failed',
            'date_encoded' => '2026-05-16'
        ],
        [
            'student_id' => '2023-00129',
            'student_name' => 'Apolinario Mabini',
            'course' => 'BEED - Elementary Education',
            'year_level' => '3rd Year',
            'semester' => '2nd Semester',
            'subject_code' => 'EDUC 301',
            'subject_name' => 'Assessment of Learning',
            'instructor' => 'Dr. Sarah Lee',
            'pre_mid' => 94,
            'midterm' => 95,
            'pre_final' => 96,
            'final' => 97,
            'final_grade' => 95.50,
            'remarks' => 'Passed',
            'date_encoded' => '2026-05-17'
        ]
    ];
}

// ✅ AYUSIN ANG LUMANG DATA - DAGDAGAN NG KULANG NA COLUMN KUNG WALA PA
foreach ($_SESSION['grade_data'] as $key => $val) {
    if (!isset($val['pre_mid'])) $_SESSION['grade_data'][$key]['pre_mid'] = 0;
    if (!isset($val['pre_final'])) $_SESSION['grade_data'][$key]['pre_final'] = 0;
    if (!isset($val['final_grade'])) $_SESSION['grade_data'][$key]['final_grade'] = 0;
    if (!isset($val['remarks'])) $_SESSION['grade_data'][$key]['remarks'] = 'N/A';
}

// ✅ ADD GRADE
if (isset($_POST['add_grade'])) {
    $pre_mid = floatval($_POST['pre_mid']);
    $mid = floatval($_POST['midterm']);
    $pre_final = floatval($_POST['pre_final']);
    $fin = floatval($_POST['final']);
    
    // ✅ COMPUTATION: (PreMid + Midterm + PreFinal + Final) / 4
    $final_g = number_format(($pre_mid + $mid + $pre_final + $fin)/4, 2, '.', '');
    $remarks = ($final_g >= 75) ? 'Passed' : 'Failed';

    $_SESSION['grade_data'][] = [
        'student_id' => $_POST['student_id'],
        'student_name' => $_POST['student_name'],
        'course' => $_POST['course'],
        'year_level' => $_POST['year_level'],
        'semester' => $_POST['semester'],
        'subject_code' => $_POST['subject_code'],
        'subject_name' => $_POST['subject_name'],
        'instructor' => $_POST['instructor'],
        'pre_mid' => $pre_mid,
        'midterm' => $mid,
        'pre_final' => $pre_final,
        'final' => $fin,
        'final_grade' => $final_g,
        'remarks' => $remarks,
        'date_encoded' => date('Y-m-d')
    ];
    header("Location: admin_grades.php?success=added");
    exit;
}

// ✅ DELETE GRADE
if (isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    foreach ($_SESSION['grade_data'] as $key => $val) {
        if ($val['student_id'] == $del_id) {
            unset($_SESSION['grade_data'][$key]);
            $_SESSION['grade_data'] = array_values($_SESSION['grade_data']);
            header("Location: admin_grades.php?success=deleted");
            exit;
        }
    }
}

// ✅ SEARCH & FILTER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_course = isset($_GET['course_filter']) ? $_GET['course_filter'] : 'All Programs';
$filter_sem = isset($_GET['sem_filter']) ? $_GET['sem_filter'] : 'All Semesters';
$filter_remark = isset($_GET['remark_filter']) ? $_GET['remark_filter'] : 'All Remarks';

$grades = $_SESSION['grade_data'];

if ($search != '') {
    $grades = array_filter($grades, fn($g) => 
        stripos($g['student_id'], $search) !== false || 
        stripos($g['student_name'], $search) !== false ||
        stripos($g['subject_code'], $search) !== false
    );
}
if ($filter_course != 'All Programs') {
    $grades = array_filter($grades, fn($g) => $g['course'] == $filter_course);
}
if ($filter_sem != 'All Semesters') {
    $grades = array_filter($grades, fn($g) => $g['semester'] == $filter_sem);
}
if ($filter_remark != 'All Remarks') {
    $grades = array_filter($grades, fn($g) => $g['remarks'] == $filter_remark);
}

// ✅ STATISTICS
$total_records = count($_SESSION['grade_data']);
$total_passed = count(array_filter($_SESSION['grade_data'], fn($g) => $g['remarks'] == 'Passed'));
$total_failed = count(array_filter($_SESSION['grade_data'], fn($g) => $g['remarks'] == 'Failed'));
$avg_gpa = $total_records > 0 ? number_format(array_sum(array_column($_SESSION['grade_data'], 'final_grade')) / $total_records, 2) : '0.00';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        /* ==============================================
✅ BASE & VARIABLES - PAREHONG PAREHO SA STUDENT
============================================== */
:root {
    /* ✅ COLOR PALETTE */
    --primary: #1e40af;
    --primary-light: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --gray: #64748b;
    --text-muted: #64748b;

    /* ✅ LIGHT MODE COLORS */
    --bg-color: #f1f5f9;
    --card-bg: #ffffff;
    --text-color: #1e293b;
    --border-color: #cbd5e1;
    --announce-bg: #f8fafc;
}

/* ✅ DARK MODE COLORS - KATUGMA NG SCREENSHOT MO */
body.dark-mode {
    --bg-color: #0f172a;
    --card-bg: #1e293b;
    --text-color: #f8fafc;
    --border-color: #334155;
    --announce-bg: #273449;
}

* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
    transition: all 0.3s ease; 
    font-family: 'Segoe UI', Roboto, sans-serif; 
}

body { 
    background-color: var(--bg-color); 
    color: var(--text-color); 
    overflow-x: hidden;
    overflow-y: auto;
}

/* ==============================================
✅ LAYOUT STRUCTURE
============================================== */
.app-container {
    display: flex;
    min-height: 100vh;
    position: relative;
}

/* ==============================================
✅ SIDEBAR - AYOS NA AYOS, GAYANG-GAYA
============================================== */
.sidebar {
    width: 260px;
    background-color: var(--card-bg);
    border-right: 1px solid var(--border-color);
    position: fixed;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* ✅ PARA NASA ILALIM LAGI ANG LOGOUT */
    overflow-y: auto;
    z-index: 100;
}

.sidebar-header {
    padding: 20px 16px;
    text-align: center;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 10px;
    flex-shrink: 0;
}

.sidebar-header img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin-bottom: 8px;
}

.sidebar-header h2 {
    font-size: 1.1rem;
    color: var(--primary-light);
    font-weight: 700;
}

.sidebar-header p {
    font-size: 0.75rem;
    color: var(--gray);
}

.sidebar-header hr {
    border: none;
    border-top: 1px solid var(--border-color);
    margin: 10px 0;
}

.nav-links {
    list-style: none;
    padding: 0 8px;
    flex-grow: 1;
    overflow-y: auto;
}

.nav-links li {
    margin-bottom: 2px;
}

.nav-links li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    color: var(--text-color);
    text-decoration: none;
    font-size: 0.9rem;
    border-radius: 6px;
    margin: 0 4px;
    opacity: 0.8;
}

.nav-links li a:hover {
    background-color: rgba(59, 130, 246, 0.1);
    color: var(--primary-light);
    opacity: 1;
}

.nav-links li a.active {
    background-color: rgba(59, 130, 246, 0.15);
    color: var(--primary-light);
    font-weight: 600;
    opacity: 1;
}

/* ✅ LOGOUT BUTTON - BUO, PULA, NASA ILALIM */
.logout-btn {
    padding: 12px 12px 20px 12px;
    margin-top: auto;
    border-top: 1px solid var(--border-color);
    background-color: var(--card-bg);
    flex-shrink: 0;
}

.logout-btn a {
    display: block;
    padding: 12px;
    background-color: var(--danger) !important;
    color: white !important;
    text-align: center;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: transform 0.2s;
}

.logout-btn a:hover {
    opacity: 0.9;
    transform: scale(0.98);
}

/* ==============================================
✅ MAIN CONTENT
============================================== */
.main-content { 
    margin-left: 260px;
    flex: 1;
    min-height: 100vh; 
    padding: 20px 24px;
}

/* ==============================================
✅ DARK MODE SWITCH
============================================== */
.mode-switch {
    position: fixed;
    top: 20px;
    right: 30px;
    z-index: 99;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-color);
    background-color: var(--card-bg);
    padding: 6px 10px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 22px;
}

.switch input { opacity: 0; width: 0; height: 0; }

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    border-radius: 20px;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: .4s;
}

input:checked + .slider { background-color: var(--primary-light); }
input:checked + .slider:before { transform: translateX(22px); }

/* ==============================================
✅ WELCOME CARD
============================================== */
.welcome-card { 
    background: linear-gradient(135deg, var(--primary), var(--primary-light)); 
    color: white; 
    padding: 24px 28px; 
    border-radius: 12px; 
    margin-bottom: 24px; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.welcome-card h1 { 
    font-size: 1.4rem; 
    margin-bottom: 8px; 
    font-weight: 600;
}

.info-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 0.9rem;
    opacity: 0.92;
}

.semester-badge {
    background-color: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.8rem;
}

.type-regular { color: #86efac; font-weight: 600; }
.type-irregular { color: #fcd34d; font-weight: 600; }

/* ==============================================
✅ STATISTICS / CARDS
============================================== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 24px 16px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-card h3 {
    font-size: 0.85rem;
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.num {
    font-size: 1.8rem;
    font-weight: 700;
}

.blue { color: var(--primary-light); }
.red { color: var(--danger); }
.green { color: var(--success); }
.zero { color: var(--gray); }

/* ==============================================
✅ ANNOUNCEMENT SECTION
============================================== */
.announcement-card {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.announcement-card h2 {
    font-size: 1.1rem;
    color: var(--text-color);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.95rem;
    opacity: 0.9;
}

.announcement-card h2 small {
    font-size: 0.75rem;
    opacity: 0.6;
    font-weight: normal;
    text-transform: none;
}

.announcement-item {
    padding: 14px 16px;
    background-color: var(--announce-bg);
    border-left: 3px solid var(--primary-light);
    border-radius: 4px;
    margin-bottom: 10px;
    transition: all 0.2s ease;
}

.announcement-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.announcement-item h4 {
    font-size: 0.95rem;
    color: var(--text-color);
    margin-bottom: 4px;
    font-weight: 600;
}

.announcement-item p {
    font-size: 0.85rem;
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 6px;
}

.announcement-item small {
    font-size: 0.75rem;
    color: var(--text-color);
    opacity: 0.6;
}

/* ==============================================
✅ RESPONSIVE / GUMAGANA SA LAHAT
============================================== */
@media (max-width: 1024px) {
    .sidebar { width: 220px; }
    .main-content { margin-left: 220px; padding: 16px; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .sidebar { 
        width: 0; 
        transform: translateX(-100%); 
        box-shadow: none;
    }
    .sidebar.active { 
        width: 260px; 
        transform: translateX(0); 
        box-shadow: 5px 0 15px rgba(0,0,0,0.2);
    }
    .main-content { margin-left: 0; width: 100%; padding: 12px; }
    .stats-row { grid-template-columns: 1fr; }
    .mode-switch { right: 15px; top: 15px; }
    .welcome-card h1 { font-size: 1.1rem; }
}

/* ==============================================
✅ CUSTOM SCROLLBAR - NAKATAGO KAPAG HINDI GINAGAMIT
============================================== */

/* Para sa Firefox */
* {
  scrollbar-width: thin;
  scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
}

/* Para sa Chrome, Safari, Edge */
::-webkit-scrollbar {
  width: 6px; /* Lapad ng scrollbar */
  height: 6px;
}

/* Background ng daanan */
::-webkit-scrollbar-track {
  background: transparent; 
}

/* Ang pang-ikot mismo */
::-webkit-scrollbar-thumb {
  background-color: transparent; /* Default: NAKATAGO / WALANG KULAY */
  border-radius: 10px;
  transition: background-color 0.3s ease;
}

/* Lalabas / MAGIGING KULAY kapag nag-hover o ini-scroll */
*:hover::-webkit-scrollbar-thumb,
*:active::-webkit-scrollbar-thumb {
  background-color: rgba(59, 130, 246, 0.4); /* Kulay asul na katugma ng tema mo */
}

/* Itago talaga kapag hindi gumagalaw */
.sidebar, .main-content {
  overflow-y: auto;
  scrollbar-gutter: stable;
}

        /* ✅ CUSTOM STYLES FOR GRADE MANAGEMENT */
        .modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.65);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
            padding: 10px;
        }
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            width: 100%;
            max-width: 550px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.25s ease;
            padding: 24px;
            border: 1px solid var(--border-color);
        }
        @keyframes fadeIn { from {opacity:0; transform:scale(0.96);} to {opacity:1; transform:scale(1);} }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .modal-header h3 { color: var(--success); font-size: 1.1rem; font-weight: 600; }
        .close-btn { color: var(--gray); font-size: 22px; cursor: pointer; transition: color 0.2s; }
        .close-btn:hover { color: var(--danger); }
        .form-group { margin-bottom: 14px; }
        .form-row { display: flex; gap: 12px; margin-bottom: 14px; }
        .form-col { flex: 1; }
        label { display: block; margin-bottom: 5px; font-size: 0.8rem; font-weight: 500; color: var(--text-color); opacity: 0.8; }
        .required { color: var(--danger); }
        input, select {
            width: 100%;
            padding: 10px 14px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-color);
            font-size: 0.85rem;
            height: 42px;
        }
        input:focus, select:focus { outline: none; border-color: var(--success); box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1); }
        .btn-submit {
            width: 100%;
            padding: 11px;
            background-color: var(--success);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }
        .btn-submit:hover { background-color: #059669; }
        .filter-bar {
            background: var(--card-bg);
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            border: 1px solid var(--border-color);
        }
        .filter-bar input, .filter-bar select { height: 38px; font-size: 0.8rem; flex: 1; min-width: 140px; }
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
            display: inline-block;
        }
        .action-btn:hover { background: #059669; }
        .table-container {
            background: var(--card-bg);
            border-radius: 10px;
            overflow: auto;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th { background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 10px 8px; text-align: left; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
        td { padding: 10px 8px; border-bottom: 1px solid var(--border-color); font-size: 0.8rem; color: var(--text-color); white-space: nowrap; }
        tr:hover { background-color: var(--bg-color); }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; }
        .passed { background: #d1fae5; color: #065f46; }
        .failed { background: #fee2e2; color: #991b1b; }
        .conditional { background: #fef3c7; color: #92400e; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 0.85rem; }
        .alert-success { background: #065F46; color: #D1FAE5; border: 1px solid #10B981; }
        .btn-sm { padding: 4px 8px; border: none; border-radius: 4px; font-size: 0.7rem; cursor: pointer; text-decoration: none; display: inline-block; margin: 0 2px; }
        .btn-view { background: var(--primary-light); color: white; }
        .btn-edit { background: var(--warning); color: white; }
        .btn-delete { background: var(--danger); color: white; }
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
                <p>👨‍💻 Welcome, Administrator!</p>
            </div>
            <ul class="nav-links">
                <li><a href="admindashboard.php">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php" class="active">📝 Grades Management</a></li>
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
                <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h1>📝 Grades Management System</h1>
                        <p style="font-size:0.85rem; opacity:0.9; margin-top:5px;">Manage student academic records, grades, and transcripts</p>
                    </div>
                    <button onclick="openModal()" class="action-btn">+ Encode New Grade</button>
                </div>
            </div>

            <!-- ✅ SUCCESS MESSAGE -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Record successfully <?= $_GET['success'] == 'added' ? 'encoded and saved!' : 'removed from database!' ?>
            </div>
            <?php endif; ?>

            <!-- ✅ STATISTICS -->
            <div class="stats-row">
                <div class="stat-card"><h3>Total Records</h3><div class="num blue"><?= $total_records ?></div></div>
                <div class="stat-card"><h3>Passed</h3><div class="num green"><?= $total_passed ?></div></div>
                <div class="stat-card"><h3>Failed</h3><div class="num red"><?= $total_failed ?></div></div>
                <div class="stat-card"><h3>Average Grade</h3><div class="num zero"><?= $avg_gpa ?></div></div>
            </div>

            <!-- ✅ SEARCH & FILTER -->
            <div class="filter-bar">
                <form method="GET" style="display: contents; width:100%;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search ID, Name or Subject...">
                    
                    <select name="course_filter" onchange="this.form.submit()">
                        <option>All Programs</option>
                        <option <?= $filter_course=='BEED - Elementary Education'?'selected':'' ?>>BEED - Elementary Education</option>
                        <option <?= $filter_course=='BSOA - Business Office Administration'?'selected':'' ?>>BSOA - Business Office Administration</option>
                        <option <?= $filter_course=='BS Business Administration'?'selected':'' ?>>BS Business Administration</option>
                        <option <?= $filter_course=='BS Information Systems'?'selected':'' ?>>BS Information Systems</option>
                        <option <?= $filter_course=='BS Criminology'?'selected':'' ?>>BS Criminology</option>
                    </select>

                    <select name="sem_filter" onchange="this.form.submit()">
                        <option>All Semesters</option>
                        <option <?= $filter_sem=='1st Semester'?'selected':'' ?>>1st Semester</option>
                        <option <?= $filter_sem=='2nd Semester'?'selected':'' ?>>2nd Semester</option>
                        <option <?= $filter_sem=='Summer'?'selected':'' ?>>Summer</option>
                    </select>

                    <select name="remark_filter" onchange="this.form.submit()">
                        <option>All Remarks</option>
                        <option <?= $filter_remark=='Passed'?'selected':'' ?>>Passed</option>
                        <option <?= $filter_remark=='Failed'?'selected':'' ?>>Failed</option>
                        <option <?= $filter_remark=='Conditional'?'selected':'' ?>>Conditional</option>
                    </select>

                    <button type="submit" class="action-btn" style="height:38px; padding:0 15px;">🔍 Go</button>
                    <?php if ($search != '' || $filter_course != 'All Programs' || $filter_sem != 'All Semesters' || $filter_remark != 'All Remarks'): ?>
                    <a href="admin_grades.php" class="action-btn" style="background:#EF4444; text-decoration:none; height:38px; padding:0 15px; display:flex; align-items:center; justify-content:center;">❌ Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- ✅ GRADES TABLE -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program / Year</th>
                            <th>Subject</th>
                            <th>Pre-Mid</th>
                            <th>Midterm</th>
                            <th>Pre-Final</th>
                            <th>Finals</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($grades) == 0): ?>
                            <tr><td colspan="11" style="text-align:center; color:var(--gray); padding:30px;">😕 No records found matching your criteria.</td></tr>
                        <?php else: ?>
                        <?php foreach ($grades as $g): ?>
                        <tr>
                            <td><strong style="color:var(--primary-light)"><?= $g['student_id'] ?></strong></td>
                            <td><strong><?= $g['student_name'] ?></strong></td>
                            <td>
                                <?= $g['course'] ?><br>
                                <span style="font-size:0.7rem; color:var(--gray)"><?= $g['year_level'] ?> | <?= $g['semester'] ?></span>
                            </td>
                            <td>
                                <?= $g['subject_code'] ?><br>
                                <span style="font-size:0.7rem; color:var(--gray)"><?= $g['subject_name'] ?></span>
                            </td>
                            <td><?= $g['pre_mid'] ?></td>
                            <td><?= $g['midterm'] ?></td>
                            <td><?= $g['pre_final'] ?></td>
                            <td><?= $g['final'] ?></td>
                            <td><strong style="font-size:0.9rem;"><?= $g['final_grade'] ?></strong></td>
                            <td><span class="badge <?= strtolower($g['remarks']) ?>"><?= $g['remarks'] ?></span></td>
                            <td>
                                <div style="display:flex; gap:2px;">
                                    <button class="btn-sm btn-view">👁️</button>
                                    <button class="btn-sm btn-edit">✏️</button>
                                    <a href="admin_grades.php?delete=<?= $g['student_id'] ?>" onclick="return confirm('⚠️ Delete this record?')" class="btn-sm btn-delete">🗑️</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ✅ MODAL: ENCODE GRADE -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📝 Encode New Student Grade</h3>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>

            <form method="POST" id="gradeForm">
                <!-- Row 1: ID & Year Level -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Student ID <span class="required">*</span></label>
                        <input type="text" name="student_id" placeholder="e.g. 2023-00101" required>
                    </div>
                    <div class="form-col">
                        <label>Year Level <span class="required">*</span></label>
                        <select name="year_level" required>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Name & Course -->
                <div class="form-group">
                    <label>Student Full Name <span class="required">*</span></label>
                    <input type="text" name="student_name" placeholder="Lastname, Firstname M." required>
                </div>

                <div class="form-group">
                    <label>Course / Program <span class="required">*</span></label>
                    <select name="course" required>
                        <option>BEED - Elementary Education</option>
                        <option>BSOA - Business Office Administration</option>
                        <option>BS Business Administration</option>
                        <option>BS Information Systems</option>
                        <option>BS Criminology</option>
                    </select>
                </div>

                <!-- Row 3: Subject Info -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Subject Code <span class="required">*</span></label>
                        <input type="text" name="subject_code" placeholder="e.g. IT 101" required>
                    </div>
                    <div class="form-col">
                        <label>Semester <span class="required">*</span></label>
                        <select name="semester" required>
                            <option>1st Semester</option>
                            <option>2nd Semester</option>
                            <option>Summer</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Subject Description <span class="required">*</span></label>
                    <input type="text" name="subject_name" placeholder="e.g. Introduction to Computing" required>
                </div>

                <div class="form-group">
                    <label>Instructor <span class="required">*</span></label>
                    <input type="text" name="instructor" placeholder="Name of Teacher" required>
                </div>

                <!-- ✅ UPDATED: GRADES INPUTS (4 COLUMNS) -->
                <div class="form-row">
                    <div class="form-col">
                        <label>Pre-Midterm <span class="required">*</span></label>
                        <input type="number" name="pre_mid" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-col">
                        <label>Midterm <span class="required">*</span></label>
                        <input type="number" name="midterm" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-col">
                        <label>Pre-Final <span class="required">*</span></label>
                        <input type="number" name="pre_final" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-col">
                        <label>Finals <span class="required">*</span></label>
                        <input type="number" name="final" min="0" max="100" step="0.01" required>
                    </div>
                </div>

                <button type="submit" name="add_grade" class="btn-submit">✅ Save Grade Record</button>
            </form>
        </div>
    </div>

    <script src="../admin_script.js"></script>
    <script>
        // ✅ MODAL CONTROL
        function openModal() {
            document.getElementById('addModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        window.onclick = function(e) {
            const modal = document.getElementById('addModal');
            if (e.target === modal) closeModal();
        };

        // ✅ DARK MODE TOGGLE
        const toggle = document.getElementById('darkmode');
        if(localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            toggle.checked = true;
        }
        toggle.addEventListener('change', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', this.checked);
        });
    </script>
</body>
</html>