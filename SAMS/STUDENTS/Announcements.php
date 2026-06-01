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

// ✅ SAMPLE ANNOUNCEMENTS DATA - NAAYOS NA ANG SULAT!
$announcements = [
    [
        'id' => 1,
        'title' => '📢 IMPORTANT: NO CLASSES TOMORROW',
        'category' => 'Holiday',
        'content' => 'Please be informed that there will be NO CLASSES tomorrow, May 27, 2026, in celebration of the City Fiesta. Offices will be closed at 3:00 PM today. Classes will resume on Wednesday.',
        'date' => 'May 26, 2026 - 10:00 AM',
        'priority' => 'high',
        'posted_by' => 'Office of the President'
    ],
    [
        'id' => 2,
        'title' => '⚠️ DEADLINE: SUBMISSION OF GRADES',
        'category' => 'Academic',
        'content' => 'To all faculty members: Deadline for submission of Final Grades is on May 30, 2026. No extensions will be given. Students can view their grades starting June 1 via the Student Portal.',
        'date' => 'May 25, 2026 - 02:15 PM',
        'priority' => 'high',
        'posted_by' => 'Registrar Office'
    ],
    [
        'id' => 3,
        'title' => '🎓 SCHEDULE OF GRADUATION RITES',
        'category' => 'Event',
        'content' => 'The 75th Commencement Exercises will be held on June 15, 2026, 8:00 AM at the Grand Coliseum. Rehearsal is on June 14, 1:00 PM. All graduating students must attend the practice.',
        'date' => 'May 24, 2026 - 09:30 AM',
        'priority' => 'medium',
        'posted_by' => 'Student Affairs'
    ],
    [
        'id' => 4,
        'title' => '🏟️ SPORTS FEST 2026 UPDATE',
        'category' => 'Activity',
        'content' => 'Opening Parade is moved to June 5, 7:00 AM due to weather forecast. Team captains meeting will be tomorrow at the Gymnasium Conference Room, 4:00 PM.',
        'date' => 'May 22, 2026 - 11:20 AM',
        'priority' => 'medium',
        'posted_by' => 'Sports Coordinator'
    ],
    [
        'id' => 5,
        'title' => '💳 ONLINE PAYMENT MAINTENANCE',
        'category' => 'System',
        'content' => 'Online payment gateway will be unavailable on May 28 (Saturday) from 12:00 MN to 4:00 AM for scheduled system maintenance. Please plan your transactions accordingly.',
        'date' => 'May 20, 2026 - 01:45 PM',
        'priority' => 'low',
        'posted_by' => 'IT Department'
    ],
    [
        'id' => 6,
        'title' => '📚 LIBRARY EXTENDED HOURS',
        'category' => 'Services',
        'content' => 'During examination period, the Library will be open from 7:00 AM to 7:00 PM, Monday to Saturday. Borrowing limit is increased to 5 books per student.',
        'date' => 'May 18, 2026 - 10:00 AM',
        'priority' => 'low',
        'posted_by' => 'Library Services'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Announcements | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ CUSTOM STYLE PARA SA ANNOUNCEMENTS PAGE */
        .announce-container {
            margin-top: 20px;
            width: 100%;
        }
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
        .filter-btn {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-color);
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .filter-btn.active {
            background-color: #2563EB;
            color: white;
            border-color: #2563EB;
        }

        /* ✅ STYLE BAWAT ANNOUNCEMENT ITEM */
        .announce-item {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .announce-item:hover {
            transform: translateY(-2px);
        }

        /* ✅ KULAY AYON SA IMPORTANCE */
        .priority-high { border-left: 5px solid #ef4444; }    /* RED - URGENT */
        .priority-medium { border-left: 5px solid #eab308; } /* YELLOW - IMPORTANT */
        .priority-low { border-left: 5px solid #3B82F6; }   /* BLUE - INFO */

        .announce-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            cursor: pointer;
            border-bottom: 1px solid transparent;
        }
        .announce-header:hover {
            background-color: rgba(59, 130, 246, 0.03);
            border-bottom: 1px solid var(--border-color);
        }
        .announce-meta h3 {
            margin: 0 0 5px 0;
            font-size: 15px;
            color: var(--text-color);
        }
        .announce-category {
            font-size: 11px;
            background-color: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-right: 8px;
        }
        .announce-date {
            font-size: 12px;
            color: var(--text-muted);
        }
        .announce-arrow {
            font-size: 18px;
            color: var(--text-muted);
            transition: transform 0.3s;
        }

        /* ✅ LAMAN NG ANNOUNCEMENT (NAKATAGO DEFAULT) */
        .announce-body {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            border-top: 1px solid var(--border-color);
        }
        .announce-body.show {
            padding: 15px 20px;
            max-height: 500px; /* Sapat na para sa mahabang text */
        }
        .announce-content {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-color);
            margin-bottom: 10px;
        }
        .announce-footer {
            font-size: 12px;
            color: var(--text-muted);
            text-align: right;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="app-container">

        <!-- ✅ SIDEBAR - ANNOUNCEMENTS ANG ACTIVE -->
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
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php">📑 Requirements</a></li>
                <li><a href="Announcements.php" class="active">🔔 Announcements</a></li> <!-- ✅ ACTIVE -->
                <li><a href="settings.php">⚙️ Settings</a></li>
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
                    <h1>🔔 Announcements & Notices</h1>
                    <p>Official updates, events, and important memorandums from the administration</p>
                </div>
            </div>

            <!-- ✅ FILTER / KATEGORYA BUTTONS -->
            <div class="filter-bar">
                <button class="filter-btn active" onclick="filterAnnounce('all', this)">All</button>
                <button class="filter-btn" onclick="filterAnnounce('high', this)">⚠️ Urgent</button>
                <button class="filter-btn" onclick="filterAnnounce('academic', this)">📚 Academic</button>
                <button class="filter-btn" onclick="filterAnnounce('event', this)">🎉 Events</button>
                <button class="filter-btn" onclick="filterAnnounce('system', this)">⚙️ System</button>
            </div>

            <!-- ✅ LISTAHAN NG ANNOUNCEMENTS -->
            <div class="announce-container" id="announceList">

                <?php foreach($announcements as $ann): ?>
                <div class="announce-item priority-<?php echo $ann['priority']; ?>" data-category="<?php echo strtolower($ann['category']); ?>">
                    
                    <!-- ✅ PAMAGAT (KINCLICK PARA BUKSAN) -->
                    <div class="announce-header" onclick="toggleAnnounce(this)">
                        <div class="announce-meta">
                            <h3><?php echo $ann['title']; ?></h3>
                            <span class="announce-category"><?php echo $ann['category']; ?></span>
                            <span class="announce-date"><?php echo $ann['date']; ?></span>
                        </div>
                        <div class="announce-arrow">▼</div>
                    </div>

                    <!-- ✅ LAMAN / DETALYE (NAKATAGO) -->
                    <div class="announce-body">
                        <div class="announce-content">
                            <?php echo $ann['content']; ?>
                        </div>
                        <div class="announce-footer">
                            Posted by: <?php echo $ann['posted_by']; ?>
                        </div>
                    </div>

                </div>
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

        // ✅ FUNCTION: BUKSA / TAKPAN ANG ANNOUNCEMENT DETALYE
        function toggleAnnounce(element) {
            const body = element.nextElementSibling;
            const arrow = element.querySelector('.announce-arrow');

            if(body.classList.contains('show')) {
                body.classList.remove('show');
                arrow.style.transform = "rotate(0deg)";
            } else {
                body.classList.add('show');
                arrow.style.transform = "rotate(180deg)";
            }
        }

        // ✅ FUNCTION: FILTER AYON SA KATEGORYA
        function filterAnnounce(type, btnElement) {
            // Palitan ang active class sa buttons
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            const items = document.querySelectorAll('.announce-item');
            
            items.forEach(item => {
                const category = item.getAttribute('data-category');
                const priority = item.classList.contains('priority-high') ? 'high' : '';

                if(type === 'all') {
                    item.style.display = 'block';
                } else if(type === 'high' && priority === 'high') {
                    item.style.display = 'block';
                } else if(type !== 'high' && category.includes(type)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function confirmLogout() {
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

</body>
</html>