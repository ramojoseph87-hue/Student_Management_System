<?php
session_start();

// ✅ DETALYE NG GURO
if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['teacher_id'] = 'TCH-001';
    $_SESSION['teacher_name'] = 'Mr. Joseph';
    $_SESSION['department'] = "Computer Studies Department";
}

// ✅ LISTAHAN NG LAHAT NG KLASE AT ASIGNATURA (pwede ito palitan galing sa DB mamaya)
$all_classes = [
    [
        'id' => 'CS101-1A',
        'code' => 'CS 101',
        'subject' => 'Introduction to Computing',
        'section' => '1-A',
        'course' => 'BS Computer Science',
        'students' => 42,
        'schedule' => 'Monday / Thursday | 08:00 AM - 09:30 AM',
        'room' => 'Room 204',
        'status' => 'Ongoing'
    ],
    [
        'id' => 'CS202-2B',
        'code' => 'CS 202',
        'subject' => 'Programming 1',
        'section' => '2-B',
        'course' => 'BS Computer Science',
        'students' => 38,
        'schedule' => 'Tuesday / Friday | 10:00 AM - 11:30 AM',
        'room' => 'Lab 1',
        'status' => 'Ongoing'
    ],
    [
        'id' => 'IT105-1C',
        'code' => 'IT 105',
        'subject' => 'Computer Networks',
        'section' => '1-C',
        'course' => 'BS Information Technology',
        'students' => 40,
        'schedule' => 'Wednesday | 01:00 PM - 03:00 PM',
        'room' => 'Room 301',
        'status' => 'Ongoing'
    ],
    [
        'id' => 'PE101-1D',
        'code' => 'PE 101',
        'subject' => 'Physical Education 1',
        'section' => '1-D',
        'course' => 'General Education',
        'students' => 45,
        'schedule' => 'Monday | 02:00 PM - 04:00 PM',
        'room' => 'Gymnasium',
        'status' => 'Ongoing'
    ],
    [
        'id' => 'MATH101-2A',
        'code' => 'MATH 101',
        'subject' => 'Mathematics in the Modern World',
        'section' => '2-A',
        'course' => 'BS Information Technology',
        'students' => 35,
        'schedule' => 'Thursday | 01:00 PM - 03:00 PM',
        'room' => 'Room 102',
        'status' => 'Ended'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classes | SAMS - STAC</title>

    <!-- ✅ SAME STYLE -->
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">

    <style>
        /* ✅ DAGDAG NA DESIGN PARA SA PAHINANG ITO */
        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .class-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .class-card:hover {
            transform: translateY(-5px);
        }
        .class-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .code-badge {
            background-color: var(--primary);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-ongoing { background: var(--success); color: white; }
        .status-ended { background: var(--gray); color: white; }
        .class-details p {
            margin: 6px 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .class-actions {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="app-container">

        <!-- ✅ SIDEBAR (PAREHO LANG, AKTIBO NGAYON ANG MY CLASSES) -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="STAC Logo">
                <h2>SAMS | TEACHER</h2>
                <p>Faculty Portal</p>
                <hr>
                <p class="teacher-info">👨‍🏫 <?= $_SESSION['teacher_name'] ?></p>
                <p style="font-size: 11px; opacity: 0.7; margin-top: 4px;"><?= $_SESSION['department'] ?></p>
            </div>

            <ul class="nav-links">
                <li><a href="teacher_dashboard.php">🏠 Dashboard</a></li>
                <li><a href="teacher_classes.php" class="active">🏫 My Classes</a></li>
                <li><a href="teacher_students.php">👨‍🎓 Students</a></li>
                <li><a href="teacher_grades.php">📝 Grades Management</a></li>
                <li><a href="teacher_attendance.php">📋 Attendance</a></li>
                <li><a href="teacher_announcements.php">📢 Announcements</a></li>
                <li><a href="teacher_settings.php">⚙️ Settings</a></li>
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

            <!-- ✅ PAGE HEADER -->
            <div class="welcome-card">
                <h1>🏫 My Classes & Subjects</h1>
                <div class="info-row">
                    <span>List of all subjects and sections you are handling</span>
                    <span class="badge">Total: <?= count($all_classes) ?> Classes</span>
                </div>
            </div>

            <!-- ✅ FILTER / SEARCH AREA -->
            <div class="card" style="padding:15px; margin-bottom:15px;">
                <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                    <div>
                        <label style="font-size:0.85rem; margin-right:5px;">Filter:</label>
                        <select style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);">
                            <option>All Subjects</option>
                            <option>Computer Science</option>
                            <option>Information Technology</option>
                            <option>General Education</option>
                        </select>
                    </div>
                    <div style="margin-left:auto;">
                        <input type="text" placeholder="Search subject or section..." style="padding:6px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color); width:250px;">
                    </div>
                </div>
            </div>

            <!-- ✅ LIST OF CLASSES -->
            <div class="class-grid">
                <?php foreach($all_classes as $cls): ?>
                <div class="class-card">
                    <div class="class-header">
                        <span class="code-badge"><?= $cls['code'] ?></span>
                        <span class="status-badge status-<?= strtolower($cls['status']) ?>"><?= $cls['status'] ?></span>
                    </div>

                    <h3 style="margin-bottom:8px; color:var(--primary);"><?= $cls['subject'] ?></h3>
                    <p style="font-size:0.85rem; opacity:0.7; margin-bottom:12px;"><?= $cls['course'] ?></p>

                    <div class="class-details">
                        <p>👥 <strong>Section:</strong> <?= $cls['section'] ?></p>
                        <p>👨‍🎓 <strong>Students:</strong> <?= $cls['students'] ?> enrollees</p>
                        <p>🕒 <strong>Schedule:</strong> <?= $cls['schedule'] ?></p>
                        <p>📍 <strong>Room:</strong> <?= $cls['room'] ?></p>
                    </div>

                    <div class="class-actions">
                        <a href="teacher_view_class.php?id=<?= $cls['id'] ?>" class="btn-sm action-btn btn-primary">View Class</a>
                        <a href="teacher_attendance.php?subj=<?= $cls['code'] ?>" class="btn-sm action-btn">Attendance</a>
                        <a href="teacher_grades.php?subj=<?= $cls['code'] ?>" class="btn-sm action-btn">Grades</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <!-- ✅ SAME SCRIPT -->
    <script src="teacher.js"></script>
</body>
</html>