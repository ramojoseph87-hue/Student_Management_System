<?php
session_start();
include "../STUDENTS/config.php";

// ✅ SAMPLE DATA
if (!isset($_SESSION['req_data'])) {
    $_SESSION['req_data'] = [
        [
            'req_id' => 'REQ-0001',
            'student_id' => '2023-00125',
            'student_name' => 'Juan Dela Cruz',
            'course' => 'BS Information Systems',
            'document_name' => 'Form 137 / Permanent Record',
            'submitted_date' => '2026-05-02',
            'status' => 'Submitted',
            'remarks' => 'Complete & Verified',
            'updated_by' => 'Admin'
        ],
        [
            'req_id' => 'REQ-0002',
            'student_id' => '2023-00126',
            'student_name' => 'Maria Santos',
            'course' => 'BS Information Systems',
            'document_name' => 'Good Moral Certificate',
            'submitted_date' => '2026-05-05',
            'status' => 'Submitted',
            'remarks' => 'OK',
            'updated_by' => 'Admin'
        ],
        [
            'req_id' => 'REQ-0003',
            'student_id' => '2023-00127',
            'student_name' => 'Jose Rizal',
            'course' => 'BS Business Administration',
            'document_name' => 'Medical Certificate',
            'submitted_date' => '',
            'status' => 'Missing',
            'remarks' => 'Not yet submitted',
            'updated_by' => ''
        ]
    ];
}

// ✅ ADD NEW RECORD - INAYOS KO NA ITO, SIGURADONG GUMAGANA!
if (isset($_POST['btn_add_new'])) { 
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $course = $_POST['course'];
    $document_name = $_POST['document_name'];
    $status = $_POST['status'];
    $submitted_date = ($status == 'Submitted') ? $_POST['submitted_date'] : '';
    $remarks = $_POST['remarks'];

    // AUTO GENERATE ID
    $bilang = count($_SESSION['req_data']) + 1;
    $new_id = 'REQ-' . str_pad($bilang, 4, '0', STR_PAD_LEFT);

    // IPASOK SA SESSION
    $_SESSION['req_data'][] = [
        'req_id' => $new_id,
        'student_id' => $student_id,
        'student_name' => $student_name,
        'course' => $course,
        'document_name' => $document_name,
        'submitted_date' => $submitted_date,
        'status' => $status,
        'remarks' => $remarks,
        'updated_by' => 'Admin'
    ];

    header("Location: admin_requirements.php?success=added");
    exit;
}

// ✅ DELETE FUNCTION
if (isset($_GET['delete'])) {
    $id_bura = $_GET['delete'];
    foreach ($_SESSION['req_data'] as $key => $val) {
        if ($val['req_id'] == $id_bura) {
            unset($_SESSION['req_data'][$key]);
            $_SESSION['req_data'] = array_values($_SESSION['req_data']);
            header("Location: admin_requirements.php?success=deleted");
            exit;
        }
    }
}

// ✅ VIEW FUNCTION - KUKUNIN ANG DETALYE KUNG MAY PININDOT KA
$view_data = null;
if (isset($_GET['view_id'])) {
    $view_id = $_GET['view_id'];
    foreach ($_SESSION['req_data'] as $val) {
        if ($val['req_id'] == $view_id) {
            $view_data = $val; // Ito ang ipapakita sa modal
            break;
        }
    }
}

// ✅ SEARCH AT FILTER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_course = isset($_GET['course_filter']) ? $_GET['course_filter'] : 'All Programs';
$filter_status = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'All Status';

$requirements = $_SESSION['req_data'];

if ($search != '') {
    $requirements = array_filter($requirements, function($r) use ($search) {
        return (stripos($r['student_id'], $search) !== false || stripos($r['student_name'], $search) !== false || stripos($r['document_name'], $search) !== false);
    });
}
if ($filter_course != 'All Programs') {
    $requirements = array_filter($requirements, function($r) use ($filter_course) {
        return ($r['course'] == $filter_course);
    });
}
if ($filter_status != 'All Status') {
    $requirements = array_filter($requirements, function($r) use ($filter_status) {
        return ($r['status'] == $filter_status);
    });
}

// ✅ COUNT STATISTICS
$total_records = count($_SESSION['req_data']);
$total_submitted = count(array_filter($_SESSION['req_data'], function($r) { return $r['status'] == 'Submitted'; }));
$total_pending = count(array_filter($_SESSION['req_data'], function($r) { return $r['status'] == 'Pending'; }));
$total_missing = count(array_filter($_SESSION['req_data'], function($r) { return $r['status'] == 'Missing'; }));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
:root {
    --primary: #1e40af;
    --primary-light: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --gray: #64748b;
    --bg-color: #f1f5f9;
    --card-bg: #ffffff;
    --text-color: #1e293b;
    --border-color: #cbd5e1;
}
body.dark-mode {
    --bg-color: #0f172a;
    --card-bg: #1e293b;
    --text-color: #f8fafc;
    --border-color: #334155;
}
* { margin: 0; padding: 0; box-sizing: border-box; transition: all 0.3s ease; font-family: 'Segoe UI, Roboto, sans-serif; }
body { background-color: var(--bg-color); color: var(--text-color); overflow-x: hidden; }
.app-container { display: flex; min-height: 100vh; position: relative; }
.sidebar { width: 260px; background-color: var(--card-bg); border-right: 1px solid var(--border-color); position: fixed; height: 100vh; display: flex; flex-direction: column; justify-content: space-between; overflow-y: auto; z-index: 100; }
.sidebar-header { padding: 20px 16px; text-align: center; border-bottom: 1px solid var(--border-color); margin-bottom: 10px; flex-shrink: 0; }
.sidebar-header img { width: 60px; height: 60px; object-fit: contain; margin-bottom: 8px; }
.sidebar-header h2 { font-size: 1.1rem; color: var(--primary-light); font-weight: 700; }
.sidebar-header p { font-size: 0.75rem; color: var(--gray); }
.sidebar-header hr { border: none; border-top: 1px solid var(--border-color); margin: 10px 0; }
.nav-links { list-style: none; padding: 0 8px; flex-grow: 1; overflow-y: auto; }
.nav-links li { margin-bottom: 2px; }
.nav-links li a { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text-color); text-decoration: none; font-size: 0.9rem; border-radius: 6px; margin: 0 4px; opacity: 0.8; }
.nav-links li a:hover { background-color: rgba(59, 130, 246, 0.1); color: var(--primary-light); opacity: 1; }
.nav-links li a.active { background-color: rgba(59, 130, 246, 0.15); color: var(--primary-light); font-weight: 600; opacity: 1; }
.logout-btn { padding: 12px 12px 20px 12px; margin-top: auto; border-top: 1px solid var(--border-color); background-color: var(--card-bg); flex-shrink: 0; }
.logout-btn a { display: block; padding: 12px; background-color: var(--danger) !important; color: white !important; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
.logout-btn a:hover { opacity: 0.9; }
.main-content { margin-left: 260px; flex: 1; min-height: 100vh; padding: 20px 24px; }
.mode-switch { position: fixed; top: 20px; right: 30px; z-index: 99; display: flex; align-items: center; gap: 8px; color: var(--text-color); background-color: var(--card-bg); padding: 6px 10px; border-radius: 20px; border: 1px solid var(--border-color); }
.switch { position: relative; display: inline-block; width: 44px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; border-radius: 20px; transition: .4s; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
input:checked + .slider { background-color: var(--primary-light); }
input:checked + .slider:before { transform: translateX(22px); }
.welcome-card { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 24px 28px; border-radius: 12px; margin-bottom: 24px; }
.welcome-card h1 { font-size: 1.4rem; margin-bottom: 8px; font-weight: 600; }
.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background-color: var(--card-bg); border: 1px solid var(--border-color); padding: 24px 16px; border-radius: 10px; text-align: center; transition: transform 0.2s; }
.stat-card:hover { transform: translateY(-4px); }
.stat-card h3 { font-size: 0.85rem; color: var(--text-color); opacity: 0.7; margin-bottom: 12px; font-weight: 500; text-transform: uppercase; }
.num { font-size: 1.8rem; font-weight: 700; }
.blue { color: var(--primary-light); }
.red { color: var(--danger); }
.green { color: var(--success); }
.yellow { color: var(--warning); }
.filter-bar { background: var(--card-bg); padding: 16px; border-radius: 10px; margin-bottom: 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; border: 1px solid var(--border-color); }
.filter-bar input, .filter-bar select { height: 38px; font-size: 0.8rem; flex: 1; min-width: 140px; padding: 0 8px; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-color); border-radius: 6px; }
.action-btn { padding: 10px 18px; background: var(--success); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; text-decoration: none; font-size: 0.85rem; }
.action-btn:hover { background: #059669; }
.table-container { background: var(--card-bg); border-radius: 10px; overflow: auto; border: 1px solid var(--border-color); }
table { width: 100%; border-collapse: collapse; min-width: 900px; }
th { background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 10px 8px; text-align: left; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
td { padding: 10px 8px; border-bottom: 1px solid var(--border-color); font-size: 0.8rem; color: var(--text-color); white-space: nowrap; }
tr:hover { background-color: var(--bg-color); }
.badge { padding: 3px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; }
.submitted { background: #d1fae5; color: #065f46; }
.pending { background: #fef3c7; color: #92400e; }
.missing { background: #fee2e2; color: #991b1b; }
.alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 0.85rem; }
.alert-success { background: #065F46; color: #D1FAE5; border: 1px solid #10B981; }
.btn-sm { padding: 4px 8px; border: none; border-radius: 4px; font-size: 0.7rem; cursor: pointer; text-decoration: none; display: inline-block; margin: 0 2px; }
.btn-view { background: var(--primary-light); color: white; }
.btn-edit { background: var(--warning); color: white; }
.btn-delete { background: var(--danger); color: white; }
.modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.65); align-items: center; justify-content: center; backdrop-filter: blur(3px); padding: 10px; }
.modal-content { background-color: var(--card-bg); color: var(--text-color); width: 100%; max-width: 550px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.2); animation: fadeIn 0.25s ease; padding: 24px; border: 1px solid var(--border-color); }
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
input, select, textarea { width: 100%; padding: 10px 14px; background-color: var(--bg-color); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-color); font-size: 0.85rem; }
input:focus, select:focus, textarea:focus { outline: none; border-color: var(--success); }
.btn-submit { width: 100%; padding: 11px; background-color: var(--success); color: white; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer; margin-top: 8px; }
.btn-submit:hover { background-color: #059669; }
.detail-row { padding: 8px 0; border-bottom: 1px solid var(--border-color); }
.detail-label { font-weight: 600; width: 140px; display: inline-block; color: var(--gray); }
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
                <p>👨‍💻 Welcome, Administrator!</p>
            </div>
            <ul class="nav-links">
                <li><a href="admindashboard.php">🏠 Dashboard</a></li>
                <li><a href="admin_people.php">👥 People Management</a></li>
                <li><a href="admin_subjects.php">📚 Subjects & Schedule</a></li>
                <li><a href="admin_grades.php">📝 Grades Management</a></li>
                <li><a href="admin_announcements.php">📢 Announcements</a></li>
                <li><a href="admin_payments.php">💰 Payments & Finance</a></li>
                <li><a href="admin_requirements.php" class="active">📂 Requirements</a></li>
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
                <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h1>📂 Student Requirements</h1>
                        <p style="font-size:0.85rem; opacity:0.9; margin-top:5px;">Monitor submitted documents, clearance, and school requirements</p>
                    </div>
                    <button onclick="openModal('addModal')" class="action-btn">+ Add New Requirement</button>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if($_GET['success'] == 'added'): ?> ✅ Record successfully saved! <?php endif; ?>
                <?php if($_GET['success'] == 'deleted'): ?> ✅ Record successfully removed! <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="stats-row">
                <div class="stat-card"><h3>Total Records</h3><div class="num blue"><?= $total_records ?></div></div>
                <div class="stat-card"><h3>Submitted</h3><div class="num green"><?= $total_submitted ?></div></div>
                <div class="stat-card"><h3>Pending</h3><div class="num yellow"><?= $total_pending ?></div></div>
                <div class="stat-card"><h3>Missing</h3><div class="num red"><?= $total_missing ?></div></div>
            </div>

            <div class="filter-bar">
                <form method="GET" style="display: contents; width:100%;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search ID, Name or Document...">
                    <select name="course_filter" onchange="this.form.submit()">
                        <option>All Programs</option>
                        <option <?= ($filter_course=='BEED - Elementary Education')?'selected':'' ?>>BEED - Elementary Education</option>
                        <option <?= ($filter_course=='BSOA - Business Office Administration')?'selected':'' ?>>BSOA - Business Office Administration</option>
                        <option <?= ($filter_course=='BS Business Administration')?'selected':'' ?>>BS Business Administration</option>
                        <option <?= ($filter_course=='BS Information Systems')?'selected':'' ?>>BS Information Systems</option>
                        <option <?= ($filter_course=='BS Criminology')?'selected':'' ?>>BS Criminology</option>
                    </select>
                    <select name="status_filter" onchange="this.form.submit()">
                        <option>All Status</option>
                        <option <?= ($filter_status=='Submitted')?'selected':'' ?>>Submitted</option>
                        <option <?= ($filter_status=='Pending')?'selected':'' ?>>Pending</option>
                        <option <?= ($filter_status=='Missing')?'selected':'' ?>>Missing</option>
                    </select>
                    <button type="submit" class="action-btn" style="height:38px; padding:0 15px;">🔍 Go</button>
                    <?php if ($search != '' || $filter_course != 'All Programs' || $filter_status != 'All Status'): ?>
                    <a href="admin_requirements.php" class="action-btn" style="background:#EF4444; text-decoration:none; height:38px; padding:0 15px; display:flex; align-items:center; justify-content:center;">❌ Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Req ID</th>
                            <th>Student Info</th>
                            <th>Document / Requirement</th>
                            <th>Date Submitted</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requirements) == 0): ?>
                            <tr><td colspan="7" style="text-align:center; color:var(--gray); padding:30px;">😕 No records found.</td></tr>
                        <?php else: ?>
                        <?php foreach ($requirements as $r): ?>
                        <tr>
                            <td><strong style="color:var(--primary-light)"><?= $r['req_id'] ?></strong></td>
                            <td>
                                <?= $r['student_name'] ?><br>
                                <span style="font-size:0.7rem; color:var(--gray)"><?= $r['student_id'] ?></span>
                            </td>
                            <td><strong><?= $r['document_name'] ?></strong></td>
                            <td><?= ($r['submitted_date']) ? $r['submitted_date'] : '<span style="color:var(--gray)">---</span>' ?></td>
                            <td><span style="font-size:0.75rem;"><?= $r['remarks'] ?></span></td>
                            <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                            <td>
                                <div style="display:flex; gap:2px;">
                                    <!-- ✅ VIEW BUTTON - PAG PINDOT LALABAS LAHAT NG DETALYE -->
                                    <a href="admin_requirements.php?view_id=<?= $r['req_id'] ?>" class="btn-sm btn-view">👁️ View</a>
                                    <button class="btn-sm btn-edit">✏️ Edit</button>
                                    <a href="admin_requirements.php?delete=<?= $r['req_id'] ?>" onclick="return confirm('⚠️ Delete this record?')" class="btn-sm btn-delete">🗑️ Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ✅ MODAL: ADD NEW REQUIREMENT -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📂 Add New Requirement</h3>
                <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
            </div>
            <!-- ✅ INAYOS KO NA ANG NAME NG BUTTON DITO -->
            <form method="POST" action="admin_requirements.php">
                <div class="form-row">
                    <div class="form-col">
                        <label>Student ID <span class="required">*</span></label>
                        <input type="text" name="student_id" placeholder="e.g. 2023-00130" required>
                    </div>
                    <div class="form-col">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="">-- Select --</option>
                            <option value="Submitted">Submitted</option>
                            <option value="Pending">Pending</option>
                            <option value="Missing">Missing</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Student Name <span class="required">*</span></label>
                    <input type="text" name="student_name" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <label>Course / Program <span class="required">*</span></label>
                    <select name="course" required>
                        <option value="">-- Select Program --</option>
                        <option>BEED - Elementary Education</option>
                        <option>BSOA - Business Office Administration</option>
                        <option>BS Business Administration</option>
                        <option>BS Information Systems</option>
                        <option>BS Criminology</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Document / Requirement Name <span class="required">*</span></label>
                    <input type="text" name="document_name" placeholder="e.g. PSA Birth Certificate, Form 137..." required>
                </div>

                <div class="form-group">
                    <label>Date Submitted</label>
                    <input type="date" name="submitted_date">
                </div>

                <div class="form-group">
                    <label>Remarks / Notes</label>
                    <textarea name="remarks" rows="3" placeholder="Any details or comments..."></textarea>
                </div>

                <!-- ✅ TAMANG PANGALAN: btn_add_new -->
                <button type="submit" name="btn_add_new" class="btn-submit">✅ Save Record</button>
            </form>
        </div>
    </div>

    <!-- ✅ MODAL: VIEW DETAILS - PARA MA-CHECK MO KUNG MERON O WALA -->
    <div id="viewModal" class="modal" style="<?= $view_data ? 'display:flex;' : 'display:none;' ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h3>👁️ View Requirement Details</h3>
                <span class="close-btn" onclick="closeModal('viewModal')">&times;</span>
            </div>
            <?php if($view_data): ?>
                <div class="detail-row"><span class="detail-label">Req ID:</span> <strong style="color:var(--primary-light)"><?= $view_data['req_id'] ?></strong></div>
                <div class="detail-row"><span class="detail-label">Student ID:</span> <?= $view_data['student_id'] ?></div>
                <div class="detail-row"><span class="detail-label">Full Name:</span> <?= $view_data['student_name'] ?></div>
                <div class="detail-row"><span class="detail-label">Course:</span> <?= $view_data['course'] ?></div>
                <div class="detail-row"><span class="detail-label">Document:</span> <?= $view_data['document_name'] ?></div>
                <div class="detail-row"><span class="detail-label">Date Submitted:</span> <?= $view_data['submitted_date'] ? $view_data['submitted_date'] : '<span style="color:var(--gray)">Not Submitted</span>' ?></div>
                <div class="detail-row"><span class="detail-label">Status:</span> <span class="badge <?= strtolower($view_data['status']) ?>"><?= $view_data['status'] ?></span></div>
                <div class="detail-row"><span class="detail-label">Remarks:</span> <?= $view_data['remarks'] ? $view_data['remarks'] : '<span style="color:var(--gray)">No remarks</span>' ?></div>
                <div class="detail-row"><span class="detail-label">Updated By:</span> <?= $view_data['updated_by'] ? $view_data['updated_by'] : '<span style="color:var(--gray)">---</span>' ?></div>
            <?php else: ?>
                <p style="text-align:center; color:var(--gray)">😕 No record selected or record not found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../admin_script.js"></script>
    <script>
        function openModal(id) { 
            document.getElementById(id).style.display = 'flex'; 
            document.body.style.overflow = 'hidden'; 
        }
        function closeModal(id) { 
            document.getElementById(id).style.display = 'none'; 
            document.body.style.overflow = 'auto'; 
            // Linisin ang url para mawala ang view_id kapag isinara
            if(id === 'viewModal') {
                window.location.href = "admin_requirements.php";
            }
        }
        window.onclick = function(e) { 
            const addModal = document.getElementById('addModal');
            const viewModal = document.getElementById('viewModal');
            if (e.target === addModal) closeModal('addModal');
            if (e.target === viewModal) closeModal('viewModal');
        }
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