<?php
session_start();
include "../STUDENTS/config.php";

// ✅ DEFAULT DATA - TAMA AT TUGMA ANG PANGALAN NG KURSO
if (!isset($_SESSION['student_data'])) {
    $_SESSION['student_data'] = [
        ['id' => 'STU-2026-0001', 'name' => 'Juan Dela Cruz', 'course' => 'BSIS - BS Information Systems', 'year' => '3rd Year', 'status' => 'Enrolled'],
        ['id' => 'STU-2026-0002', 'name' => 'Maria Clara Santos', 'course' => 'BSOA - Business Office Administration', 'year' => '2nd Year', 'status' => 'Pending'],
        ['id' => 'STU-2026-0003', 'name' => 'Jose Reyes', 'course' => 'BSBA - Business Administration', 'year' => '4th Year', 'status' => 'Enrolled'],
        ['id' => 'STU-2026-0004', 'name' => 'Ana Rivera', 'course' => 'BEED - Elementary Education', 'year' => '1st Year', 'status' => 'Not Enrolled'],
        ['id' => 'STU-2026-0005', 'name' => 'Mark Lopez', 'course' => 'BSCRIM - Criminology', 'year' => '3rd Year', 'status' => 'Enrolled'],
        ['id' => 'STU-2026-0006', 'name' => 'Sarah Johnson', 'course' => 'BSIS - BS Information Systems', 'year' => '2nd Year', 'status' => 'Enrolled'],
        ['id' => 'STU-2026-0007', 'name' => 'Michael Lim', 'course' => 'BSCRIM - Criminology', 'year' => '3rd Year', 'status' => 'Pending'],
        ['id' => 'STU-2026-0008', 'name' => 'Rizalina Cruz', 'course' => 'BSBA - Business Administration', 'year' => '2nd Year', 'status' => 'Enrolled'],
    ];
}

// ✅ ADD STUDENT FUNCTION - TAMA ANG ISE-SAVE NA PANGALAN NG KURSO
if (isset($_POST['add_student'])) {
    $new_id = 'STU-2026-' . str_pad(count($_SESSION['student_data']) + 1, 4, '0', STR_PAD_LEFT);
    $_SESSION['student_data'][] = [
        'id' => $new_id,
        'name' => trim($_POST['name']),
        'course' => $_POST['course'], // ✅ TUGMA SA DROPDOWN
        'year' => $_POST['year'],
        'status' => $_POST['status']
    ];
    header("Location: adminstudent.php?success=added");
    exit;
}

// ✅ DELETE STUDENT FUNCTION
if (isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    foreach ($_SESSION['student_data'] as $key => $val) {
        if ($val['id'] == $del_id) {
            unset($_SESSION['student_data'][$key]);
            $_SESSION['student_data'] = array_values($_SESSION['student_data']);
            header("Location: adminstudent.php?success=deleted");
            exit;
        }
    }
}

// ✅ SEARCH & FILTER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_course = isset($_GET['course_filter']) ? $_GET['course_filter'] : 'All Programs / Courses';
$filter_status = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'All Status';

$students = $_SESSION['student_data'];

if ($search != '') {
    $students = array_filter($students, fn($s) => 
        stripos($s['id'], $search) !== false || stripos($s['name'], $search) !== false
    );
}
// ✅ FILTER LOGIC - SIGURADONG TUGMA
if ($filter_course != 'All Programs / Courses') {
    $students = array_filter($students, fn($s) => $s['course'] == $filter_course);
}
if ($filter_status != 'All Status') {
    $students = array_filter($students, fn($s) => $s['status'] == $filter_status);
}

// ✅ STATISTICS
$total = count($_SESSION['student_data']);
$enrolled = count(array_filter($_SESSION['student_data'], fn($s) => $s['status'] == 'Enrolled'));
$pending = count(array_filter($_SESSION['student_data'], fn($s) => $s['status'] == 'Pending'));
$not_enrolled = count(array_filter($_SESSION['student_data'], fn($s) => $s['status'] == 'Not Enrolled'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        /* ✅ IMPROVED DESIGN */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-top: 12px;
        }
        table { width: 100%; border-collapse: collapse; }
        table th {
            background-color: rgba(59, 130, 246, 0.12);
            color: var(--primary-light);
            padding: 16px 14px;
            text-align: left;
            font-weight: 700;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 2px solid var(--border-color);
        }
        table td {
            padding: 16px 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 0.95rem;
        }
        table tr:hover { 
            background-color: rgba(59, 130, 246, 0.06); 
            transition: background 0.2s ease;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
            text-align: center;
            min-width: 90px;
        }
        .status-enrolled { background-color: #10B981; color: #fff; }
        .status-pending { background-color: #F59E0B; color: #fff; }
        .status-not { background-color: #EF4444; color: #fff; }

        .action-buttons { display: flex; gap: 6px; }
        .action-buttons button {
            border: none;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease;
        }
        .action-buttons button:hover { transform: scale(1.05); }
        .btn-view { background-color: #3B82F6; color: #fff; }
        .btn-edit { background-color: #F59E0B; color: #fff; }
        .btn-delete { background-color: #EF4444; color: #fff; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }
        .action-btn {
            padding: 12px 18px !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            background-color: #10B981 !important;
            color: #fff !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            transition: background 0.2s ease;
        }
        .action-btn:hover { background-color: #059669 !important; }

        /* ✅ MODAL DESIGN */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.75);
            padding-top: 60px;
            backdrop-filter: blur(4px);
        }
        .modal-content {
            background-color: var(--card-bg);
            margin: auto;
            padding: 25px;
            border: 1px solid var(--border-color);
            width: 90%;
            max-width: 480px;
            border-radius: 14px;
            color: var(--text-color);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            animation: modalFade 0.3s ease;
        }
        @keyframes modalFade {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-content h3 {
            color: var(--primary-light);
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 1.2rem;
        }
        .modal-content label {
            font-weight: 600;
            font-size: 0.9rem;
            display: block;
            margin: 10px 0 4px;
        }
        .modal-content input, .modal-content select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-color);
            font-size: 0.95rem;
        }
        .modal-content button[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 16px;
            background: #10B981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
        }
        .modal-content button[type="submit"]:hover { background: #059669; }
        .close {
            color: var(--gray);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            margin-top: -8px;
        }
        .close:hover { color: var(--text-color); }

        /* ✅ ALERT MESSAGE */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 600;
            text-align: center;
        }
        .alert-success { background-color: #D1FAE5; color: #065F46; border: 1px solid #10B981; }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- ✅ ADMIN SIDEBAR -->
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
                <li><a href="admin_people.php" class="active">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php">📂 Requirements</a></li>
                <li><a href="admin_settings.php">⚙️ System Settings</a></li>
                <li><a href="../STUDENTS/Dashboard_Student.php">👤 Go to Student View</a></li>
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
                        <h1>👥 Student Management</h1>
                        <div class="info-row">
                            <span>Manage all student records, accounts, and information easily</span>
                        </div>
                    </div>
                    <button onclick="openModal()" class="action-btn">+ Add New Student</button>
                </div>
            </div>

            <!-- ✅ SUCCESS ALERT -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Record successfully <?= $_GET['success'] == 'added' ? 'added!' : 'deleted!' ?>
            </div>
            <?php endif; ?>

            <!-- ✅ STATISTICS -->
            <div class="stats-row">
                <div class="stat-card"><h3>TOTAL STUDENTS</h3><div class="num blue"><?= $total ?></div></div>
                <div class="stat-card"><h3>ENROLLED</h3><div class="num green"><?= $enrolled ?></div></div>
                <div class="stat-card"><h3>PENDING</h3><div class="num red"><?= $pending ?></div></div>
                <div class="stat-card"><h3>NOT ENROLLED</h3><div class="num zero"><?= $not_enrolled ?></div></div>
            </div>

            <!-- ✅ SEARCH & FILTER - TAMA AT TUGMA NA ANG LAHAT -->
            <div class="announcement-card" style="margin-bottom: 16px; padding: 18px;">
                <form method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search by ID or Full Name..." style="flex:1; min-width: 260px; padding: 12px 15px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size: 0.95rem;">
                    
                    <select name="course_filter" onchange="this.form.submit()" style="padding: 12px 15px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size: 0.95rem;">
                        <option>All Programs / Courses</option>
                        <option <?= $filter_course=='BEED - Elementary Education'?'selected':'' ?>>BEED - Elementary Education</option>
                        <option <?= $filter_course=='BSOA - Business Office Administration'?'selected':'' ?>>BSOA - Business Office Administration</option>
                        <option <?= $filter_course=='BSBA - Business Administration'?'selected':'' ?>>BSBA - Business Administration</option>
                        <option <?= $filter_course=='BSIS - BS Information Systems'?'selected':'' ?>>BSIS - BS Information Systems</option>
                        <option <?= $filter_course=='BSCRIM - Criminology'?'selected':'' ?>>BSCRIM - Criminology</option>
                    </select>

                    <select name="status_filter" onchange="this.form.submit()" style="padding: 12px 15px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-size: 0.95rem;">
                        <option>All Status</option>
                        <option <?= $filter_status=='Enrolled'?'selected':'' ?>>Enrolled</option>
                        <option <?= $filter_status=='Pending'?'selected':'' ?>>Pending</option>
                        <option <?= $filter_status=='Not Enrolled'?'selected':'' ?>>Not Enrolled</option>
                    </select>

                    <button type="submit" style="padding: 12px 20px; background:#3B82F6; color:white; border:none; border-radius:8px; font-weight:600; font-size:0.9rem;">🔍 Search</button>
                    <?php if ($search != '' || $filter_course != 'All Programs / Courses' || $filter_status != 'All Status'): ?>
                    <a href="adminstudent.php" style="padding: 12px 20px; background:#EF4444; color:white; border:none; border-radius:8px; font-weight:600; font-size:0.9rem; text-decoration:none; display:inline-block;">❌ Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- ✅ TABLE -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Course / Program</th>
                            <th>Year Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students) == 0): ?>
                            <tr><td colspan="6" style="text-align:center; color:var(--gray); padding:30px; font-size:1rem;">😕 No student records found matching your criteria.</td></tr>
                        <?php else: ?>
                        <?php foreach ($students as $s): ?>
                        <tr>
                            <td><strong style="color:var(--primary-light)"><?= $s['id'] ?></strong></td>
                            <td><strong><?= $s['name'] ?></strong></td>
                            <td><?= $s['course'] ?></td>
                            <td><?= $s['year'] ?></td>
                            <td>
                                <?php $cls = $s['status']=='Enrolled'?'status-enrolled':($s['status']=='Pending'?'status-pending':'status-not'); ?>
                                <span class="status-badge <?= $cls ?>"><?= $s['status'] ?></span>
                            </td>
                            <td class="action-buttons">
                                <button class="btn-view" title="View Details">👁️ View</button>
                                <button class="btn-edit" title="Edit Record">✏️ Edit</button>
                                <a href="adminstudent.php?delete=<?= $s['id'] ?>" onclick="return confirm('⚠️ Are you sure you want to DELETE this student record? This cannot be undone!')">
                                    <button class="btn-delete" title="Delete Record">🗑️ Del</button>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ✅ MODAL: ADD STUDENT - TUGMA NA TUGMA ANG KURSO -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>📝 Register New Student</h3>
            <form method="POST">
                <label>Full Name <span style="color:red;">*</span></label>
                <input type="text" name="name" placeholder="e.g. Juan Dela Cruz" required>

                <label>Course / Program <span style="color:red;">*</span></label>
                <select name="course" required>
                    <option value="" disabled selected>-- Select Course --</option>
                    <option>BEED - Elementary Education</option>
                    <option>BSOA - Business Office Administration</option>
                    <option>BSBA - Business Administration</option>
                    <option>BSIS - BS Information Systems</option>
                    <option>BSCRIM - Criminology</option>
                </select>

                <label>Year Level <span style="color:red;">*</span></label>
                <select name="year" required>
                    <option value="" disabled selected>-- Select Year --</option>
                    <option>1st Year</option>
                    <option>2nd Year</option>
                    <option>3rd Year</option>
                    <option>4th Year</option>
                </select>

                <label>Enrollment Status <span style="color:red;">*</span></label>
                <select name="status" required>
                    <option value="" disabled selected>-- Select Status --</option>
                    <option>Enrolled</option>
                    <option>Pending</option>
                    <option>Not Enrolled</option>
                </select>

                <button type="submit" name="add_student">✅ Save Student Record</button>
            </form>
        </div>
    </div>

    <script src="../admin_script.js"></script>
    <script>
        function openModal() { document.getElementById('addModal').style.display = 'block'; document.body.style.overflow = 'hidden'; }
        function closeModal() { document.getElementById('addModal').style.display = 'none'; document.body.style.overflow = 'auto'; }
        window.onclick = function(e) { const m = document.getElementById('addModal'); if (e.target == m) closeModal(); }
    </script>
</body>
</html>