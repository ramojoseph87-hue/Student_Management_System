<?php
session_start();
include "../STUDENTS/config.php";

// ✅ SAMPLE DATA
if (!isset($_SESSION['announcement_data'])) {
    $_SESSION['announcement_data'] = [
        [
            'ann_id' => 'ANN-2026-001',
            'title' => 'ENROLLMENT SCHEDULE UPDATE',
            'message' => 'Please be informed that the enrollment for the upcoming semester will start on June 15, 2026. All students are advised to prepare their requirements beforehand. Late enrollees will be subject to section availability only.',
            'priority' => 'High',
            'posted_by' => 'Admin / Registrar',
            'date_posted' => '2026-05-20 09:15:00'
        ],
        [
            'ann_id' => 'ANN-2026-002',
            'title' => 'NO CLASSES - SCHOOL HOLIDAY',
            'message' => 'There will be no classes on May 29, 2026 (Friday) in observance of the local city fiesta. Offices will be closed. Classes will resume on May 30, 2026 (Saturday).',
            'priority' => 'Medium',
            'posted_by' => 'Admin Office',
            'date_posted' => '2026-05-18 14:30:00'
        ]
    ];
}

// ✅ SAVE NEW ANNOUNCEMENT
if (isset($_POST['btn_create_announcement'])) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);
    $priority = $_POST['priority'];

    $bilang = count($_SESSION['announcement_data']) + 1;
    $new_id = 'ANN-' . date('Y') . '-' . str_pad($bilang, 3, '0', STR_PAD_LEFT);
    $current_datetime = date('Y-m-d H:i:s');

    $_SESSION['announcement_data'][] = [
        'ann_id' => $new_id,
        'title' => $title,
        'message' => $message,
        'priority' => $priority,
        'posted_by' => 'Administrator',
        'date_posted' => $current_datetime
    ];

    header("Location: admin_announcements.php?success=1");
    exit;
}

// ✅ DELETE
if (isset($_GET['delete_id'])) {
    $id_bura = $_GET['delete_id'];
    foreach ($_SESSION['announcement_data'] as $key => $val) {
        if ($val['ann_id'] == $id_bura) {
            unset($_SESSION['announcement_data'][$key]);
            $_SESSION['announcement_data'] = array_values($_SESSION['announcement_data']);
            header("Location: admin_announcements.php?success=2");
            exit;
        }
    }
}

// ✅ VIEW
$view_data = null;
if (isset($_GET['view_id'])) {
    $view_id = $_GET['view_id'];
    foreach ($_SESSION['announcement_data'] as $val) {
        if ($val['ann_id'] == $view_id) {
            $view_data = $val;
            break;
        }
    }
}

// ✅ SEARCH & FILTER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_priority = isset($_GET['priority_filter']) ? $_GET['priority_filter'] : 'All Priority';
$announcements = $_SESSION['announcement_data'];

if ($search != '') {
    $announcements = array_filter($announcements, fn($a) => stripos($a['title'], $search) !== false || stripos($a['message'], $search) !== false);
}
if ($filter_priority != 'All Priority') {
    $announcements = array_filter($announcements, fn($a) => $a['priority'] == $filter_priority);
}

// ✅ STATS
$total_ann = count($_SESSION['announcement_data']);
$high_priority = count(array_filter($_SESSION['announcement_data'], fn($a) => $a['priority'] == 'High'));
$medium_priority = count(array_filter($_SESSION['announcement_data'], fn($a) => $a['priority'] == 'Medium'));
$low_priority = count(array_filter($_SESSION['announcement_data'], fn($a) => $a['priority'] == 'Low'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
/* ✅ EKSAKTONG KULAY - GAYA SA DASHBOARD & SETTINGS */
:root {
    --primary: #2563EB; /* ✅ EKSAKTO KULAY */
    --primary-light: #3B82F6;
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --gray: #64748B;

    --bg-color: #F3F4F6; /* ✅ EKSAKTO BACKGROUND */
    --card-bg: #FFFFFF;
    --text-color: #1F2937;
    --border-color: #E5E7EB;
    --sidebar-bg: #FFFFFF;
    --sidebar-text: #1F2937;
}

/* ✅ DARK MODE - PAREHONG PAANO MAG BAGO */
body.dark-mode {
    --primary: #3B82F6 !important;
    --primary-light: #60A5FA !important;
    --success: #10B981 !important;
    --warning: #F59E0B !important;
    --danger: #EF4444 !important;
    --gray: #9CA3AF !important;

    --bg-color: #111827 !important;
    --card-bg: #1F2937 !important;
    --text-color: #F9FAFB !important;
    --border-color: #374151 !important;
    --sidebar-bg: #1F2937 !important;
    --sidebar-text: #F9FAFB !important;

    color: var(--text-color) !important;
    background-color: var(--bg-color) !important;
}

* { margin: 0; padding: 0; box-sizing: border-box; transition: all 0.3s ease; font-family: 'Segoe UI, Roboto, sans-serif; }
body { background-color: var(--bg-color); color: var(--text-color); overflow-x: hidden; }
.app-container { display: flex; min-height: 100vh; position: relative; }

/* ✅ SIDEBAR - TUGMA SA IBA */
.sidebar { 
    width: 260px; 
    background-color: var(--sidebar-bg) !important; 
    border-right: 1px solid var(--border-color); 
    position: fixed; 
    height: 100vh; 
    display: flex; 
    flex-direction: column; 
    justify-content: space-between; 
    overflow-y: auto; 
    z-index: 100; 
}
.sidebar-header { padding: 20px 16px; text-align: center; border-bottom: 1px solid var(--border-color); margin-bottom: 10px; flex-shrink: 0; }
.sidebar-header img { width: 60px; height: 60px; object-fit: contain; margin-bottom: 8px; }
.sidebar-header h2 { font-size: 1.1rem; color: var(--primary); font-weight: 700; }
.sidebar-header p { font-size: 0.75rem; color: var(--gray); }
.sidebar-header hr { border: none; border-top: 1px solid var(--border-color); margin: 10px 0; }
.sidebar-header p.welcome-text { color:#93C5FD; font-weight:500; font-size:14px; }

.nav-links { list-style: none; padding: 0 8px; flex-grow: 1; overflow-y: auto; }
.nav-links li { margin-bottom: 2px; }
.nav-links li a { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--sidebar-text); text-decoration: none; font-size: 0.9rem; border-radius: 6px; margin: 0 4px; opacity: 0.8; }
.nav-links li a:hover { background-color: rgba(37, 99, 235, 0.08); color: var(--primary); opacity: 1; }
.nav-links li a.active { background-color: rgba(37, 99, 235, 0.12); color: var(--primary); font-weight: 600; opacity: 1; }

.logout-btn { padding: 12px 12px 20px 12px; margin-top: auto; border-top: 1px solid var(--border-color); background-color: var(--sidebar-bg); flex-shrink: 0; }
.logout-btn a { display: block; padding: 12px; background-color: var(--danger) !important; color: white !important; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
.logout-btn a:hover { opacity: 0.9; }

/* ✅ MAIN CONTENT */
.main-content { margin-left: 260px; flex: 1; min-height: 100vh; padding: 20px 24px; background-color: var(--bg-color) !important; }
.mode-switch { position: fixed; top: 20px; right: 30px; z-index: 99; display: flex; align-items: center; gap: 8px; color: var(--text-color); background-color: var(--card-bg); padding: 6px 10px; border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.switch { position: relative; display: inline-block; width: 44px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); border-radius: 20px; transition: .4s; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(22px); }

/* ✅ WELCOME CARD / HEADER - EKSAKTO KOPYA */
.welcome-card { 
    background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%); 
    color: white; 
    padding: 28px 32px; 
    border-radius: 12px; 
    margin-bottom: 24px; 
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}
.welcome-card .page-header {
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    flex-wrap:wrap; 
    gap:10px;
}
.welcome-card h1 { font-size: 1.5rem; margin-bottom: 8px; font-weight: 700; }
.welcome-card p { font-size: 0.9rem; opacity: 0.95; margin-top: 6px; }

/* ✅ STATS CARDS */
.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { 
    background-color: var(--card-bg); 
    border: 1px solid var(--border-color); 
    padding: 20px 16px; 
    border-radius: 10px; 
    text-align: center; 
    transition: transform 0.2s; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.stat-card:hover { transform: translateY(-4px); }
.stat-card h3 { font-size: 0.8rem; color: var(--text-color); opacity: 0.7; margin-bottom: 8px; font-weight: 500; text-transform: uppercase; }
.num { font-size: 1.8rem; font-weight: 700; }
.blue { color: var(--primary); }
.red { color: var(--danger); }
.orange { color: var(--warning); }
.green { color: var(--success); }

/* ✅ FILTER BAR */
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.filter-bar input, .filter-bar select { 
    height: 38px; 
    font-size: 0.8rem; 
    flex: 1; 
    min-width: 140px; 
    padding: 0 8px; 
    background: var(--bg-color); 
    border: 1px solid var(--border-color); 
    color: var(--text-color); 
    border-radius: 6px; 
}
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
}
.action-btn:hover { background: #059669; }

/* ✅ ANNOUNCEMENT LIST */
.announcement-list { display: flex; flex-direction: column; gap: 12px; }
.ann-card { 
    background: var(--card-bg); 
    border-radius: 10px; 
    border-left: 5px solid; 
    padding: 18px 20px; 
    transition: all 0.2s; 
    border: 1px solid var(--border-color); 
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.ann-card.high { border-left-color: var(--danger); }
.ann-card.medium { border-left-color: var(--warning); }
.ann-card.low { border-left-color: var(--success); }
.ann-card:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.ann-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; flex-wrap: wrap; gap:8px; }
.ann-title { font-size: 1rem; font-weight: 700; color: var(--text-color); }
.badge { 
    padding: 3px 10px; 
    border-radius: 12px; 
    font-size: 0.7rem; 
    font-weight: 600; 
    text-transform: uppercase; 
}
.badge.high { background: #FEE2E2; color: #991B1B; }
.badge.medium { background: #FEF3C7; color: #92400E; }
.badge.low { background: #D1FAE5; color: #065F46; }

.ann-meta { font-size: 0.75rem; color: var(--gray); margin-bottom: 10px; }
.ann-text { font-size: 0.85rem; color: var(--text-color); line-height: 1.5; margin-bottom: 12px; }
.ann-text.cut { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 100%; display: block; }

.ann-actions { display: flex; gap: 6px; justify-content: flex-end; }
.btn-sm { padding: 5px 10px; border: none; border-radius: 4px; font-size: 0.75rem; cursor: pointer; text-decoration: none; font-weight: 600; }
.btn-view { background: var(--primary); color: white; }
.btn-delete { background: var(--danger); color: white; }

.alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 0.85rem; }
.alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }

/* ✅ MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
}
.modal-content {
    background-color: var(--card-bg);
    color: var(--text-color);
    margin: 5% auto;
    padding: 25px;
    border: 1px solid var(--border-color);
    width: 90%;
    max-width: 550px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
}
.modal-header h3 { color: var(--primary); }
.close-btn { font-size: 24px; font-weight: bold; cursor: pointer; color: var(--gray); }
.close-btn:hover { color: var(--danger); }

.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-size: 0.85rem; font-weight: 600; color: var(--text-color); }
.required { color: var(--danger); }
input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--bg-color);
    color: var(--text-color);
}
textarea { min-height: 120px; }
.btn-submit {
    width: 100%;
    padding: 12px;
    background: var(--success);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    font-size: 1rem;
}
.btn-submit:hover { background: #059669; }
.detail-block { margin: 10px 0; padding-bottom: 5px; border-bottom: 1px solid var(--border-color); }

@media (max-width: 768px) { .sidebar { width: 0; transform: translateX(-100%); } .sidebar.active { width: 260px; transform: translateX(0); } .main-content { margin-left: 0; width: 100%; padding: 12px; } }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../Untitled.png" alt="School Logo">
                <h2>SAMS - ADMIN</h2>
                <p>System Management Portal</p>
                <hr>
                <p class="welcome-text">👨‍💻 Welcome, Administrator!</p>
            </div>
            <ul class="nav-links">
                <li><a href="admindashboard.php">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php" class="active">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php">📂 Requirements</a></li>
                <li><a href="admin_settings.php">⚙️ System Settings</a></li>
            </ul>
            <div class="logout-btn">
                <a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a>
            </div>
        </div>

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
                        <h1>📢 Announcements</h1>
                        <p>Post news, updates, and important information for students and staff</p>
                    </div>
                    <button onclick="document.getElementById('addModal').style.display='block'" class="action-btn" style="font-size:1rem; padding:12px 20px;">
                        + Create New Announcement
                    </button>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if($_GET['success'] == 1): ?> ✅ Announcement successfully created and posted! <?php endif; ?>
                <?php if($_GET['success'] == 2): ?> ✅ Announcement successfully removed! <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="stats-row">
                <div class="stat-card"><h3>Total Posts</h3><div class="num blue"><?= $total_ann ?></div></div>
                <div class="stat-card"><h3>High Priority</h3><div class="num red"><?= $high_priority ?></div></div>
                <div class="stat-card"><h3>Medium</h3><div class="num orange"><?= $medium_priority ?></div></div>
                <div class="stat-card"><h3>Low / Info</h3><div class="num green"><?= $low_priority ?></div></div>
            </div>

            <div class="filter-bar">
                <form method="GET" style="display: contents; width:100%;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search title or message...">
                    <select name="priority_filter" onchange="this.form.submit()">
                        <option>All Priority</option>
                        <option <?= ($filter_priority=='High')?'selected':'' ?>>High</option>
                        <option <?= ($filter_priority=='Medium')?'selected':'' ?>>Medium</option>
                        <option <?= ($filter_priority=='Low')?'selected':'' ?>>Low</option>
                    </select>
                    <button type="submit" class="action-btn" style="height:38px; padding:0 15px;">🔍 Search</button>
                    <?php if ($search != '' || $filter_priority != 'All Priority'): ?>
                    <a href="admin_announcements.php" class="action-btn" style="background:#EF4444; text-decoration:none; height:38px; padding:0 15px; display:flex; align-items:center; justify-content:center;">❌ Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="announcement-list">
                <?php if (count($announcements) == 0): ?>
                    <div style="text-align:center; color:var(--gray); padding:40px; background:var(--card-bg); border-radius:10px;">😕 No announcements created yet.</div>
                <?php else: ?>
                <?php foreach ($announcements as $a): ?>
                <div class="ann-card <?= strtolower($a['priority']) ?>">
                    <div class="ann-header">
                        <div class="ann-title"><?= $a['title'] ?></div>
                        <span class="badge <?= strtolower($a['priority']) ?>"><?= $a['priority'] ?></span>
                    </div>
                    <div class="ann-meta">📅 <?= $a['date_posted'] ?> | 📝 By: <?= $a['posted_by'] ?></div>
                    <div class="ann-text cut"><?= $a['message'] ?></div>
                    <div class="ann-actions">
                        <a href="admin_announcements.php?view_id=<?= $a['ann_id'] ?>" class="btn-sm btn-view">👁️ View Full</a>
                        <a href="admin_announcements.php?delete_id=<?= $a['ann_id'] ?>" onclick="return confirm('⚠️ Delete this announcement?')" class="btn-sm btn-delete">🗑️ Delete</a>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <!-- ✅ MODAL: CREATE NEW ANNOUNCEMENT -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📢 Create New Announcement</h3>
                <span class="close-btn" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="admin_announcements.php">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Message <span class="required">*</span></label>
                    <textarea name="message" required></textarea>
                </div>
                <div class="form-group">
                    <label>Priority <span class="required">*</span></label>
                    <select name="priority" required>
                        <option value="High">🔴 High</option>
                        <option value="Medium">🟡 Medium</option>
                        <option value="Low">🟢 Low</option>
                    </select>
                </div>
                <button type="submit" name="btn_create_announcement" class="btn-submit">✅ Post Announcement</button>
            </form>
        </div>
    </div>

    <!-- ✅ MODAL: VIEW -->
    <div id="viewModal" class="modal" style="<?= $view_data ? 'display:block;' : 'display:none;' ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📢 <?= $view_data ? $view_data['title'] : 'Details' ?></h3>
                <span class="close-btn" onclick="window.location.href='admin_announcements.php'">&times;</span>
            </div>
            <?php if($view_data): ?>
                <div class="detail-block"><strong>By:</strong> <?= $view_data['posted_by'] ?></div>
                <div class="detail-block"><strong>Date:</strong> <?= $view_data['date_posted'] ?></div>
                <div class="form-group">
                    <label>Message:</label>
                    <div style="padding:10px; background:var(--bg-color); border-radius:6px;"><?= $view_data['message'] ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ✅ SCRIPT - PAREHO NA SA LAHAT NG PAHINA -->
    <script src="../admin_script.js"></script>
    <script>
        const toggle = document.getElementById('darkmode');
        
        // ✅ TAMA ANG PAGBASA SA SETTINGS
        if(localStorage.getItem('darkMode') === 'true') { 
            document.body.classList.add('dark-mode'); 
            toggle.checked = true; 
        } else {
            document.body.classList.remove('dark-mode'); 
            toggle.checked = false;
        }

        toggle.addEventListener('change', function() { 
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'false');
            }
        });
    </script>
</body>
</html>