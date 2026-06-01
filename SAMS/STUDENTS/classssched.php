<?php
session_start();
// SAMPLE DATA LANG - WALANG DATABASE

// ✅ DITO MO ILALAGAY ANG MGA DETALYE, KASAMA NA ANG TEACHER
$schedules = [
    [
        'day' => 'Monday',
        'time' => '07:30 AM - 09:00 AM',
        'subject' => 'Introduction to Computing',
        'code' => 'CC 101',
        'room' => 'Computer Lab 101',
        'teacher' => 'Mr. John Doe',
        'color' => '#EF4444' // Kulay pula para madaling makilala
    ],
    [
        'day' => 'Monday',
        'time' => '09:00 AM - 10:30 AM',
        'subject' => 'Computer Programming 1',
        'code' => 'CS 102',
        'room' => 'IT Laboratory 203',
        'teacher' => 'Ms. Jane Smith',
        'color' => '#2563EB' // Kulay asul
    ],
    [
        'day' => 'Tuesday',
        'time' => '01:00 PM - 02:30 PM',
        'subject' => 'Understanding the Self',
        'code' => 'GE 101',
        'room' => 'Room 305 - Bldg. B',
        'teacher' => 'Dr. Michael Lee',
        'color' => '#10B981' // Kulay berde
    ],
    [
        'day' => 'Wednesday',
        'time' => '07:30 AM - 09:00 AM',
        'subject' => 'Introduction to Computing',
        'code' => 'CC 101',
        'room' => 'Computer Lab 101',
        'teacher' => 'Mr. John Doe',
        'color' => '#EF4444'
    ],
    [
        'day' => 'Wednesday',
        'time' => '02:30 PM - 04:00 PM',
        'subject' => 'College Algebra',
        'code' => 'MATH 101',
        'room' => 'Math Center 401',
        'teacher' => 'Mrs. Sarah Cruz',
        'color' => '#F59E0B' // Kulay kahel/dilaw
    ],
    [
        'day' => 'Thursday',
        'time' => '10:30 AM - 12:00 PM',
        'subject' => 'Computer Programming 1',
        'code' => 'CS 102',
        'room' => 'IT Laboratory 203',
        'teacher' => 'Ms. Jane Smith',
        'color' => '#2563EB'
    ],
    [
        'day' => 'Friday',
        'time' => '01:00 PM - 02:30 PM',
        'subject' => 'Understanding the Self',
        'code' => 'GE 101',
        'room' => 'Room 305 - Bldg. B',
        'teacher' => 'Dr. Michael Lee',
        'color' => '#10B981'
    ],
    [
        'day' => 'Saturday',
        'time' => '08:00 AM - 10:00 AM',
        'subject' => 'Physical Education 1',
        'code' => 'PE 101',
        'room' => 'Main Gymnasium',
        'teacher' => 'Mr. Mark Santos',
        'color' => '#8B5CF6' // Kulay lila
    ]
];

// Ayos ng pagkakasunod-sunod ng mga araw
$days_order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ MAGANDA AT MODERNONG DESIGN */
        .schedule-container {
            margin-top: 20px;
            width: 100%;
        }

        /* Card para sa bawat araw */
        .day-section {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease;
        }
        .day-section:hover {
            transform: translateY(-2px);
        }

        /* Header ng araw */
        .day-header {
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: white;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .day-header span {
            background-color: rgba(255,255,255,0.2);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Bawat item ng schedule */
        .schedule-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            gap: 15px;
            position: relative;
        }
        .schedule-item:last-child {
            border-bottom: none;
        }

        /* Kulay sa gilid */
        .color-strip {
            position: absolute;
            left: 0;
            top: 15px;
            bottom: 15px;
            width: 5px;
            border-radius: 0 3px 3px 0;
        }

        /* Impormasyon ng Subject */
        .subject-details {
            flex: 3;
            min-width: 250px;
            padding-left: 15px;
        }
        .subject-code {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-color);
        }
        .subject-name {
            font-size: 14px;
            color: var(--text-muted);
            margin: 3px 0 8px 0;
        }
        .teacher-name { /* ✅ PANGALAN NG GURO - PINAKA-MALINAW */
            font-size: 13px;
            color: #2563EB;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .teacher-name::before {
            content: "👤";
            font-size: 14px;
        }

        /* Oras at Room */
        .time-box, .room-box {
            flex: 1;
            min-width: 120px;
            text-align: center;
            background-color: rgba(37, 99, 235, 0.05);
            padding: 10px;
            border-radius: 8px;
        }
        .label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            display: block;
            margin-bottom: 3px;
        }
        .value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Responsive - gumaganda rin sa cellphone */
        @media (max-width: 768px) {
            .schedule-item {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            .time-box, .room-box {
                width: 100%;
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
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
                <p>Welcome, Juan!</p>
            </div>

            <ul class="nav-links">
                <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
                <li><a href="Profile.php">👤 My Profile</a></li> 
                <li><a href="add_subjects.php">➕ Add Subjects</a></li> 
                <li><a href="assessment_form.php">📄 Assessment Form</a></li> 
                <li><a href="classssched.php" class="active">🗓️ Class Schedule</a></li> <!-- ✅ ACTIVE -->
                <li><a href="view.php">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php">📑 Requirements</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="settings.php">⚙️ Settings</a></li>
                <li><a href="help.php">❓ Help & Support</a></li>
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
                <div>
                    <h1>🗓️ My Class Schedule</h1>
                    <p>First Semester | Academic Year 2024 - 2025</p>
                </div>
            </div>

            <div class="schedule-container">

                <?php foreach($days_order as $day): ?>
                    <?php 
                    // Kumuha lang ng schedule para sa araw na ito
                    $daily_sched = array_filter($schedules, function($s) use ($day) {
                        return $s['day'] == $day;
                    });
                    ?>

                    <?php if(!empty($daily_sched)): ?>
                    <div class="day-section">
                        <!-- Header: Pangalan ng Araw at Bilang ng Klase -->
                        <div class="day-header">
                            <?php echo $day; ?>
                            <span><?php echo count($daily_sched); ?> Classes</span>
                        </div>

                        <!-- Listahan ng mga klase -->
                        <?php foreach($daily_sched as $item): ?>
                        <div class="schedule-item">
                            <!-- Kulay sa gilid -->
                            <div class="color-strip" style="background-color: <?php echo $item['color']; ?>"></div>

                            <!-- Detalye ng Subject at Teacher -->
                            <div class="subject-details">
                                <div class="subject-code"><?php echo $item['code']; ?></div>
                                <div class="subject-name"><?php echo $item['subject']; ?></div>
                                <div class="teacher-name"><?php echo $item['teacher']; ?></div> <!-- ✅ TAMBAY DITO SI TEACHER -->
                            </div>

                            <!-- Oras -->
                            <div class="time-box">
                                <span class="label">Time</span>
                                <span class="value"><?php echo $item['time']; ?></span>
                            </div>

                            <!-- Room -->
                            <div class="room-box">
                                <span class="label">Room / Location</span>
                                <span class="value"><?php echo $item['room']; ?></span>
                            </div>

                        </div>
                        <?php endforeach; ?>

                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>

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