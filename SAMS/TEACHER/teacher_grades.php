<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

// Listahan ng Subjects at Estudyante para sa Grading
$subjects = [
    ['code' => 'CS101', 'name' => 'Introduction to Computing', 'section' => '1-A'],
    ['code' => 'BSIS101', 'name' => 'Programming 1', 'section' => '1-A'],
    ['code' => 'BEED102', 'name' => 'Child Development', 'section' => '1-C'],
];

$students_grades = [
    ['id' => 'STU-0045', 'name' => 'Dela Cruz, Juan A.', 'course' => 'BS Information System', 'prelim' => 85, 'midterm' => 88, 'final' => 90, 'avg' => 87.6, 'status' => 'Passed'],
    ['id' => 'STU-0122', 'name' => 'Santos, Maria B.', 'course' => 'BEEd', 'prelim' => 78, 'midterm' => 80, 'final' => 82, 'avg' => 80.0, 'status' => 'Passed'],
    ['id' => 'STU-0078', 'name' => 'Reyes, Jose C.', 'course' => 'BSCrim', 'prelim' => 75, 'midterm' => 72, 'final' => 70, 'avg' => 72.3, 'status' => 'Conditional'],
    ['id' => 'STU-0091', 'name' => 'Bautista, Ana D.', 'course' => 'BSOA', 'prelim' => 91, 'midterm' => 93, 'final' => 95, 'avg' => 93.0, 'status' => 'Passed'],
    ['id' => 'STU-0104', 'name' => 'Torres, Mark E.', 'course' => 'BSBA', 'prelim' => 65, 'midterm' => 68, 'final' => 70, 'avg' => 67.6, 'status' => 'Failed'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades Management | SAMS - STAC</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        .table-container { overflow-x: auto; background: var(--card-bg); border-radius: 12px; border:1px solid var(--border-color); margin-top:20px; }
        table { width:100%; border-collapse: collapse; }
        th { padding:12px; background:rgba(37, 99, 235, 0.08); color:var(--primary); text-align:left; font-size:0.8rem; }
        td { padding:12px; border-bottom:1px solid var(--border-color); font-size:0.9rem; }
        .status-passed {color:var(--success); font-weight:600;}
        .status-failed {color:var(--danger); font-weight:600;}
        .status-conditional {color:var(--warning); font-weight:600;}
        .btn-sm { padding:4px 8px; font-size:0.75rem; border-radius:4px; border:none; cursor:pointer;}
        .edit-btn {background:var(--primary); color:white;}
        .filter-bar {background:var(--card-bg); border:1px solid var(--border-color); border-radius:12px; padding:15px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;}
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="STAC Logo">
                <h2>SAMS | TEACHER</h2>
                <p>Faculty Portal</p>
                <hr>
                <p class="teacher-info">👨‍🏫 <?= $_SESSION['teacher_name'] ?></p>
                <p style="font-size: 11px; opacity: 0.7;"><?= $_SESSION['department'] ?></p>
            </div>
            <ul class="nav-links">
                <li><a href="teacher_dashboard.php">🏠 Dashboard</a></li>
                <li><a href="teacher_classes.php">🏫 My Classes</a></li>
                <li><a href="teacher_students.php">👨‍🎓 Students</a></li>
                <li><a href="teacher_grades.php" class="active">📝 Grades Management</a></li>
                <li><a href="teacher_attendance.php">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
            </ul>
            <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
        </div>

        <div class="main-content">
            <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

            <div class="welcome-card">
                <h1>📝 Grades Management</h1>
                <div class="info-row"><span>Input, edit and view grades of students per subject</span><span class="badge">1st Semester | A.Y. 2025-2026</span></div>
            </div>

            <div class="filter-bar">
                <div style="display:flex; gap:10px; align-items:center;">
                    <label>Select Subject:</label>
                    <select style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                        <?php foreach($subjects as $subj): ?>
                        <option value="<?= $subj['code'] ?>"><?= $subj['code'] ?> - <?= $subj['name'] ?> (<?= $subj['section'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <select style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                        <option>Prelim</option><option>Midterm</option><option>Finals</option>
                    </select>
                </div>
                <div>
                    <button class="action-btn btn-sm">📥 Export CSV</button>
                    <button class="action-btn btn-sm">🖨️ Print</button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Prelim</th>
                            <th>Midterm</th>
                            <th>Finals</th>
                            <th>Average</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students_grades as $sg): ?>
                        <tr>
                            <td><strong><?= $sg['id'] ?></strong></td>
                            <td><?= $sg['name'] ?></td>
                            <td><?= $sg['course'] ?></td>
                            <td><?= $sg['prelim'] ?></td>
                            <td><?= $sg['midterm'] ?></td>
                            <td><?= $sg['final'] ?></td>
                            <td><strong><?= $sg['avg'] ?></strong></td>
                            <td><span class="status-<?= strtolower($sg['status']) ?>"><?= $sg['status'] ?></span></td>
                            <td><button class="btn-sm edit-btn">✏️ Edit</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="teacher.js"></script>
</body>
</html>