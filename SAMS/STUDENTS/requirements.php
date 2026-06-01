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
$student_id = $user['student_id']; // Para pangalanan natin ang file

// ✅ HAWAKAN NATIN ANG PAG-UPLOAD NG FILE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["req_file"])) {
    $req_id = $_POST['req_id'];
    $target_dir = "uploads/requirements/"; // DITO IISAVE ANG FILES
    // GAWIN NATIN ANG FOLDER KUNG WALA PA
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    // PANGALAN NG FILE: StudentID_DocumentName_Date.pdf/jpg
    $file_name = $student_id . "_" . str_replace(" ", "_", $_POST['req_name']) . "_" . date("Ymd") . "." . strtolower(pathinfo($_FILES["req_file"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["req_file"]["type"]));

    // TANGGAPIN LAMANG: JPG, PNG, PDF
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf" ) {
        $error = "Sorry, only JPG, PNG, & PDF files are allowed.";
        $uploadOk = 0;
    }
    // SIZE LIMIT: 5MB
    if ($_FILES["req_file"]["size"] > 5000000) {
        $error = "Sorry, your file is too large. Maximum 5MB.";
        $uploadOk = 0;
    }
    // KUNG WALANG ERROR, ILIPAT ANG FILE
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["req_file"]["tmp_name"], $target_file)) {
            // ✅ DITO DAPAT ISAVE SA DATABASE (gagawa tayo ng table nito bukas, pero pansamantala success lang muna)
            $success = "File <b>". htmlspecialchars( basename( $_FILES["req_file"]["name"])). "</b> has been uploaded. Waiting for verification.";
            // DITO NATIN PAPALITAN ANG STATUS SA SESSION MUNA HABANG WALA PA DB
            $_SESSION['uploaded'][$req_id] = 'Pending';
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}

// ✅ LISTAHAN NG MGA KAILANGAN (ITO ANG MAGIGING LAMAN NG DATABASE)
$requirements = [
    [
        'id' => 1,
        'title' => 'PSA Birth Certificate',
        'status' => isset($_SESSION['uploaded'][1]) ? $_SESSION['uploaded'][1] : 'Missing',
        'remarks' => 'Original or Certified True Copy',
        'color' => ($_SESSION['uploaded'][1] ?? 'Missing') == 'Completed' ? '#10b981' : (($_SESSION['uploaded'][1] ?? 'Missing') == 'Pending' ? '#eab308' : '#ef4444')
    ],
    [
        'id' => 2,
        'title' => 'Form 137 / High School Record',
        'status' => isset($_SESSION['uploaded'][2]) ? $_SESSION['uploaded'][2] : 'Missing',
        'remarks' => 'From previous school',
        'color' => ($_SESSION['uploaded'][2] ?? 'Missing') == 'Completed' ? '#10b981' : (($_SESSION['uploaded'][2] ?? 'Missing') == 'Pending' ? '#eab308' : '#ef4444')
    ],
    [
        'id' => 3,
        'title' => 'Certificate of Good Moral',
        'status' => isset($_SESSION['uploaded'][3]) ? $_SESSION['uploaded'][3] : 'Completed',
        'remarks' => 'Submitted & Verified',
        'color' => '#10b981'
    ],
    [
        'id' => 4,
        'title' => '2x2 ID Pictures (4 pcs)',
        'status' => isset($_SESSION['uploaded'][4]) ? $_SESSION['uploaded'][4] : 'Completed',
        'remarks' => 'White Background',
        'color' => '#10b981'
    ],
    [
        'id' => 5,
        'title' => 'Medical Certificate',
        'status' => isset($_SESSION['uploaded'][5]) ? $_SESSION['uploaded'][5] : 'Missing',
        'remarks' => 'From School Clinic or Doctor',
        'color' => ($_SESSION['uploaded'][5] ?? 'Missing') == 'Completed' ? '#10b981' : (($_SESSION['uploaded'][5] ?? 'Missing') == 'Pending' ? '#eab308' : '#ef4444')
    ],
    [
        'id' => 6,
        'title' => 'Barangay / Residency Cert',
        'status' => isset($_SESSION['uploaded'][6]) ? $_SESSION['uploaded'][6] : 'Missing',
        'remarks' => 'Issued this month',
        'color' => ($_SESSION['uploaded'][6] ?? 'Missing') == 'Completed' ? '#10b981' : (($_SESSION['uploaded'][6] ?? 'Missing') == 'Pending' ? '#eab308' : '#ef4444')
    ]
];

// KUWENTA
$total = count($requirements);
$complete = 0; $pending = 0; $missing = 0;
foreach($requirements as $req){
    if($req['status'] == 'Completed') $complete++;
    if($req['status'] == 'Pending') $pending++;
    if($req['status'] == 'Missing') $missing++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Requirements | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ ESTILO PARA SA MODAL / POPUP */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);
        }
        .modal-content {
            background-color: var(--card-bg); margin: 10% auto; padding: 20px;
            border: 1px solid var(--border-color); width: 90%; max-width: 400px; border-radius: 8px;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom:15px; }
        .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size:13px; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius:4px; }
        .btn-sm { padding: 4px 10px; font-size: 11px; border-radius: 4px; border:none; cursor:pointer; }
        .btn-upload { background: #3B82F6; color:white; }
        .btn-view { background: #10b981; color:white; }
        .alert { padding:10px; margin-bottom:15px; border-radius:4px; font-size:13px; }
        .alert-success { background: rgba(16, 185, 129,0.1); color: #10b981; border: 1px solid #10b981; }
        .alert-danger { background: rgba(239, 68, 68,0.1); color: #ef4444; border: 1px solid #ef4444; }
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
                <p>Welcome, 
                    <?php echo !empty($user['fullname']) ? explode(' ', $user['fullname'])[0] : $user['username']; ?>!
                </p>
            </div>
            <ul class="nav-links">
                <li><a href="Dashboard_Student.php">🏠 Dashboard</a></li>
                <li><a href="Profile.php">👤 My Profile</a></li> 
                <li><a href="add_subjects.php">➕ Add Subjects</a></li> 
                <li><a href="assessment_form.php">📄 Assessment Form</a></li>
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php" class="active">📑 Requirements</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="settings.php">⚙️ Settings</a></li>
                <li><a href="help.php">❓ Help & Support</a></li>
            </ul>
            <div class="logout-btn"><a href="#" onclick="confirmLogout(); return false;">🚪 Logout</a></div>
        </div>

        <!-- ✅ MAIN CONTENT -->
        <div class="main-content">
            <div class="mode-switch"><span>☀️</span><label class="switch"><input type="checkbox" id="darkmode"><span class="slider"></span></label><span>🌙</span></div>

            <div class="welcome-card">
                <div><h1>📑 Requirements Checklist</h1><p>Submit and monitor your enrollment documents</p></div>
            </div>

            <!-- ✅ ALERT MESSAGES -->
            <?php if(isset($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if(isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <!-- ✅ STATISTICS -->
            <div class="stats-row">
                <div class="stat-card"><h3>TOTAL</h3><div class="num blue"><?php echo $total; ?></div></div>
                <div class="stat-card"><h3>COMPLETED</h3><div class="num" style="color:#10b981;"><?php echo $complete; ?></div></div>
                <div class="stat-card"><h3>PENDING</h3><div class="num" style="color:#eab308;"><?php echo $pending; ?></div></div>
                <div class="stat-card"><h3>MISSING</h3><div class="num" style="color:#ef4444;"><?php echo $missing; ?></div></div>
            </div>

            <!-- ✅ LISTAHAN MAY BUTTON NA! -->
            <div class="announcement-card">
                <h2>📋 Document Status & Action</h2>

                <?php foreach($requirements as $req): ?>
                <div class="announcement-item" style="border-left: 4px solid <?php echo $req['color']; ?>;">
                    <div class="trans-left">
                        <h4><?php echo $req['title']; ?></h4>
                        <small class="trans-date"><?php echo $req['remarks']; ?></small>
                    </div>
                    <div class="trans-right" style="display:flex; flex-direction:column; gap:5px; align-items:flex-end;">
                        <span style="color:<?php echo $req['color']; ?>; font-weight:600; font-size:12px; margin-bottom:5px;"><?php echo strtoupper($req['status']); ?></span>
                        
                        <?php if($req['status'] == 'Missing'): ?>
                            <!-- ✅ BUTTON: UPLOAD KUNG WALA PA -->
                            <button class="btn-sm btn-upload" onclick="openModal(<?php echo $req['id']; ?>, '<?php echo $req['title']; ?>')">📎 Upload</button>
                        <?php elseif($req['status'] == 'Pending'): ?>
                            <!-- ✅ BUTTON: PENDING / VIEW -->
                            <button class="btn-sm btn-view" disabled>⌔ Waiting</button>
                        <?php else: ?>
                            <!-- ✅ BUTTON: OK NA -->
                            <button class="btn-sm btn-view">👁 View</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div> 
    </div> 

    <!-- ✅ MODAL / POPUP PARA SA UPLOAD -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Upload Requirement</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="req_id" id="req_id">
                <input type="hidden" name="req_name" id="req_name">
                
                <div class="form-group">
                    <label>Select File <small>(PDF / JPG / PNG only, max 5MB!)</small></label>
                    <input type="file" name="req_file" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%;">📤 Submit Document</button>
            </form>
        </div>
    </div>

    <!-- ✅ SCRIPTS -->
    <script>
        const toggle = document.getElementById('darkmode');
        const body = document.body;
        const modal = document.getElementById("uploadModal");

        if(localStorage.getItem('darkMode') === 'enabled'){
            body.classList.add('dark-mode');
            toggle.checked = true;
        }
        toggle.addEventListener('change', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', toggle.checked ? 'enabled' : 'disabled');
        });

        function openModal(id, name) {
            document.getElementById("req_id").value = id;
            document.getElementById("req_name").value = name;
            document.getElementById("modalTitle").innerText = "Upload: " + name;
            modal.style.display = "block";
        }
        function closeModal() { modal.style.display = "none"; }
        window.onclick = function(event) { if (event.target == modal) modal.style.display = "none"; }

        function confirmLogout() { if(confirm("Are you sure?")) window.location.href = "logout.php"; }
    </script>
    <script src="student.js"></script>
</body>
</html>