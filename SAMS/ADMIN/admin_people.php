<?php
session_start();
include "../STUDENTS/config.php"; 

// ✅ SIGURADuhin MAY TABLES (MySQLi version)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,
    full_name VARCHAR(255),
    course VARCHAR(255),
    status ENUM('Enrolled','Not Enrolled') DEFAULT 'Not Enrolled',
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id VARCHAR(50) UNIQUE,
    full_name VARCHAR(255),
    subject_handled VARCHAR(255),
    schedule VARCHAR(255),
    status ENUM('Active','Inactive') DEFAULT 'Active'
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS system_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    full_name VARCHAR(255),
    role VARCHAR(100),
    status ENUM('Active','Frozen') DEFAULT 'Active'
)");

// ✅ SAVE STUDENT
if(isset($_POST['save_student'])) {
    $id = $_POST['edit_id'] ?? null;
    $sid = $_POST['student_id'];
    $name = $_POST['full_name'];
    $course = $_POST['course'];
    $stat = $_POST['status'];

    if($id){
        $q = "UPDATE students SET student_id='$sid', full_name='$name', course='$course', status='$stat' WHERE id=$id";
    } else {
        $q = "INSERT INTO students (student_id, full_name, course, status) VALUES ('$sid','$name','$course','$stat')";
    }
    mysqli_query($conn, $q);
    header("Location: admin_people.php?tab=Students&success=1"); exit;
}

// ✅ DELETE STUDENT
if(isset($_GET['del_student'])) {
    mysqli_query($conn, "DELETE FROM students WHERE id=".$_GET['del_student']);
    header("Location: admin_people.php?tab=Students"); exit;
}

// ✅ SAVE TEACHER
if(isset($_POST['save_teacher'])) {
    $id = $_POST['edit_id'] ?? null;
    $tid = $_POST['teacher_id'];
    $name = $_POST['full_name'];
    $subj = $_POST['subject_handled'];
    $sched = $_POST['schedule'];

    if($id){
        $q = "UPDATE teachers SET teacher_id='$tid', full_name='$name', subject_handled='$subj', schedule='$sched' WHERE id=$id";
    } else {
        $q = "INSERT INTO teachers (teacher_id, full_name, subject_handled, schedule) VALUES ('$tid','$name','$subj','$sched')";
    }
    mysqli_query($conn, $q);
    header("Location: admin_people.php?tab=Teachers&success=1"); exit;
}

// ✅ SAVE USER
if(isset($_POST['save_user'])) {
    $id = $_POST['edit_id'] ?? null;
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $name = $_POST['full_name'];
    $role = $_POST['role'];
    $stat = $_POST['status'];

    if($id){
        $q = "UPDATE system_users SET username='$user', full_name='$name', role='$role', status='$stat' WHERE id=$id";
        mysqli_query($conn, $q);
        if(!empty($pass)) mysqli_query($conn, "UPDATE system_users SET password='$pass' WHERE id=$id");
    } else {
        $q = "INSERT INTO system_users (username, password, full_name, role, status) VALUES ('$user','$pass','$name','$role','$stat')";
        mysqli_query($conn, $q);
    }
    header("Location: admin_people.php?tab=Access&success=1"); exit;
}

// ✅ FREEZE / UNFREEZE
if(isset($_GET['freeze'])) mysqli_query($conn, "UPDATE system_users SET status='Frozen' WHERE id=".$_GET['freeze']);
if(isset($_GET['unfreeze'])) mysqli_query($conn, "UPDATE system_users SET status='Active' WHERE id=".$_GET['unfreeze']);

// ✅ GET DATA
$students = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM students ORDER BY date_added DESC"), MYSQLI_ASSOC);
$teachers = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM teachers"), MYSQLI_ASSOC);
$users = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM system_users"), MYSQLI_ASSOC);

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'Students';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People Management | SAMS</title>
    <link rel="stylesheet" href="../admin_style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
    <style>
        * { box-sizing: border-box; }
        .tab-container { display: flex; gap: 5px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color, #334155); padding-bottom: 0; }
        .tab-btn { padding: 12px 25px; background-color: transparent; border: none; color: var(--text-gray, #94a3b8); font-weight: 600; cursor: pointer; border-radius: 8px 8px 0 0; font-size: 15px; transition: all 0.2s ease; }
        .tab-btn:hover { background-color: rgba(59, 130, 246, 0.1); color: var(--primary-light, #60a5fa); }
        .tab-btn.active { background-color: var(--bg-card, #1e293b); color: var(--primary-light, #60a5fa); border: 1px solid var(--border-color, #334155); border-bottom: 1px solid var(--bg-card, #1e293b); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* ✅ INAYOS NA MODAL - MALINAW AT SOLID */
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(15, 23, 42, 0.85); padding-top: 50px; backdrop-filter: blur(4px); }
        .modal-content { background-color: var(--bg-card, #1e293b); margin: 3% auto; padding: 25px; border: 1px solid var(--border-color, #334155); width: 50%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4); }

        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px; color: var(--text-white, #f1f5f9); }
        input, select { width: 100%; padding: 11px 14px; border: 1px solid var(--border-color, #475569); border-radius: 6px; background: var(--bg-main, #0f172a); color: var(--text-white, #f8fafc); font-size: 14px; transition: border 0.2s; }
        input:focus, select:focus { outline: none; border-color: var(--primary-light, #3b82f6); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15); }

        .btn-save { background: #10B981; color: white; border: none; padding: 11px 20px; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 14px; transition: background 0.2s; }
        .btn-save:hover { background: #059669; }
        .btn-close { background: #EF4444; color: white; border: none; padding: 11px 20px; border-radius: 6px; cursor: pointer; float:right; font-weight: 500; font-size: 14px; transition: background 0.2s; }
        .btn-close:hover { background: #DC2626; }

        .alert { padding: 12px 18px; background-color: #10B981; color: white; margin-bottom: 20px; border-radius: 8px; font-weight: 500; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2); }

        /* ✅ PINAKA-AYOS NA TABLE - MALINIS, PANTAY, MAGANDA */
        .table-container { width: 100%; overflow-x: auto; border-radius: 10px; border: 1px solid var(--border-color, #334155); background-color: var(--bg-card, #1e293b); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }

        /* ULO NG TABLE */
        table thead tr { background-color: rgba(59, 130, 246, 0.08); }
        table th { padding: 15px 20px; text-align: left; font-weight: 600; font-size: 14px; color: var(--primary-light, #93c5fd); border-bottom: 2px solid var(--border-color, #475569); white-space: nowrap; }
        table th:first-child { border-top-left-radius: 10px; }
        table th:last-child { border-top-right-radius: 10px; text-align: center; }

        /* LAMAN NG TABLE */
        table td { padding: 14px 20px; text-align: left; font-size: 14px; color: var(--text-white, #e2e8f0); border-bottom: 1px solid var(--border-color, #334155); background-color: transparent; }
        table tr:last-child td { border-bottom: none; }
        table tr:nth-child(even) td { background-color: rgba(30, 41, 59, 0.4); }
        table tr:hover td { background-color: rgba(59, 130, 246, 0.06); transition: background 0.2s; }

        /* STATUS BADGE */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; text-align: center; min-width: 90px; }
        .status-enrolled { background-color: #10B981; color: #ffffff; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
        .status-notenrolled { background-color: #EF4444; color: #ffffff; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
        .status-active { color: #10B981; font-weight: 600; }
        .status-frozen { color: #EF4444; font-weight: 600; }

        /* BUTTONS SA TABLE */
        .btn-edit { background-color: #3B82F6; color: white; border: none; padding: 6px 14px; border-radius: 5px; font-size: 12px; font-weight: 500; cursor: pointer; margin-right: 5px; transition: background 0.2s; }
        .btn-edit:hover { background-color: #2563eb; }
        .btn-delete { background-color: #EF4444; color: white; border: none; padding: 6px 14px; border-radius: 5px; font-size: 12px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-delete:hover { background-color: #dc2626; }
        .btn-freeze { background-color: #F59E0B; color: white; border: none; padding: 6px 14px; border-radius: 5px; font-size: 12px; font-weight: 500; cursor:pointer; margin-right:5px; }
        .btn-freeze:hover { background-color: #d97706; }
        .btn-unlock { background-color: #10B981; color: white; border: none; padding: 6px 14px; border-radius: 5px; font-size: 12px; font-weight: 500; cursor:pointer; margin-right:5px; }
        .btn-unlock:hover { background-color: #059669; }
        
        .text-center { text-align: center !important; }
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
                <h1>👥 People Management</h1>
                <p style="font-size:0.85rem; color:var(--gray); margin-top:5px;">Manage Students, Teachers, and System Access all in one place</p>
                <?php if(isset($_GET['success'])): ?><div class="alert">✅ Saved successfully!</div><?php endif; ?>
            </div>

            <div class="tab-container">
                <button class="tab-btn <?php echo ($activeTab=='Students')?'active':''; ?>" onclick="openTab(event, 'Students')">🎓 Students</button>
                <button class="tab-btn <?php echo ($activeTab=='Teachers')?'active':''; ?>" onclick="openTab(event, 'Teachers')">👨‍🏫 Teachers</button>
                <button class="tab-btn <?php echo ($activeTab=='Access')?'active':''; ?>" onclick="openTab(event, 'Access')">🔐 System Access</button>
            </div>

            <!-- STUDENTS TAB -->
            <div id="Students" class="tab-content announcement-card <?php echo ($activeTab=='Students')?'active':''; ?>">
                <h2>Student Records & Enrollment</h2>
                <p style="margin-bottom:15px; color:var(--gray);">Add, edit, or view student details. Manage enrollment status.</p>
                <button class="action-btn" style="margin-bottom:15px; padding: 10px 15px;" onclick="openModal('modalStudent')">+ Add New Student</button>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID No.</th>
                                <th>Full Name</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $s): ?>
                            <tr>
                                <td><?php echo $s['student_id']; ?></td>
                                <td><?php echo $s['full_name']; ?></td>
                                <td><?php echo $s['course']; ?></td>
                                <td><span class="status-badge <?php echo ($s['status']=='Enrolled')?'status-enrolled':'status-notenrolled'; ?>"><?php echo $s['status']; ?></span></td>
                                <td class="text-center">
                                    <button class="btn-edit" onclick="openModal('modalStudent', <?php echo json_encode($s); ?>)">Edit</button>
                                    <button class="btn-delete" onclick="if(confirm('Are you sure you want to delete this record?')) window.location.href='?del_student=<?php echo $s['id']; ?>'">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($students)) echo '<tr><td colspan="5" style="padding:20px; text-align:center; color:var(--text-gray);">No records found.</td></tr>'; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TEACHERS TAB -->
            <div id="Teachers" class="tab-content announcement-card <?php echo ($activeTab=='Teachers')?'active':''; ?>">
                <h2>Teacher Management & Schedules</h2>
                <p style="margin-bottom:15px; color:var(--gray);">Manage faculty records, assigned subjects, and class schedules.</p>
                <button class="action-btn" style="margin-bottom:15px; padding: 10px 15px;" onclick="openModal('modalTeacher')">+ Add New Teacher</button>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Subject Handled</th>
                                <th>Schedule</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($teachers as $t): ?>
                            <tr>
                                <td><?php echo $t['teacher_id']; ?></td>
                                <td><?php echo $t['full_name']; ?></td>
                                <td><?php echo $t['subject_handled']; ?></td>
                                <td><?php echo $t['schedule']; ?></td>
                                <td class="text-center">
                                    <button class="btn-edit" onclick="openModal('modalTeacher', <?php echo json_encode($t); ?>)">Edit</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($teachers)) echo '<tr><td colspan="5" style="padding:20px; text-align:center; color:var(--text-gray);">No records found.</td></tr>'; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SYSTEM ACCESS TAB -->
            <div id="Access" class="tab-content announcement-card <?php echo ($activeTab=='Access')?'active':''; ?>">
                <h2>System Access & Security</h2>
                <p style="margin-bottom:15px; color:var(--gray);">Control who can access the system. <b>Freeze / Lock / Unlock</b> accounts.</p>
                <button class="action-btn" style="margin-bottom:15px; padding: 10px 15px;" onclick="openModal('modalUser')">+ Create New Admin/User</button>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><?php echo $u['username']; ?></td>
                                <td><?php echo $u['full_name']; ?></td>
                                <td><?php echo $u['role']; ?></td>
                                <td><span class="<?php echo ($u['status']=='Active')?'status-active':'status-frozen'; ?>">● <?php echo $u['status']; ?></span></td>
                                <td class="text-center">
                                    <button class="btn-edit" onclick="openModal('modalUser', <?php echo json_encode($u); ?>)">Edit / Pass</button>
                                    <?php if($u['status'] == 'Active'): ?>
                                    <button class="btn-freeze" onclick="if(confirm('Are you sure you want to FREEZE this account? User cannot login anymore!')) window.location.href='?freeze=<?php echo $u['id']; ?>'">Freeze</button>
                                    <?php else: ?>
                                    <button class="btn-unlock" onclick="if(confirm('Are you sure you want to UNLOCK this account?')) window.location.href='?unfreeze=<?php echo $u['id']; ?>'">Unlock</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($users)) echo '<tr><td colspan="5" style="padding:20px; text-align:center; color:var(--text-gray);">No users found.</td></tr>'; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    <div id="modalStudent" class="modal">
        <div class="modal-content"><form method="POST"><span class="btn-close" onclick="closeModal('modalStudent')">Close</span><h3 id="modalStudentTitle">Add New Student</h3><input type="hidden" name="edit_id" id="edit_id"><div class="form-group"><label>Student ID</label><input type="text" name="student_id" id="student_id" required></div><div class="form-group"><label>Full Name</label><input type="text" name="full_name" id="full_name" required></div><div class="form-group"><label>Course / Program</label><input type="text" name="course" id="course" required></div><div class="form-group"><label>Status</label><select name="status" id="status"><option value="Enrolled">Enrolled</option><option value="Not Enrolled">Not Enrolled</option></select></div><button type="submit" name="save_student" class="btn-save">Save Student</button></form></div>
    </div>

    <div id="modalTeacher" class="modal">
        <div class="modal-content"><form method="POST"><span class="btn-close" onclick="closeModal('modalTeacher')">Close</span><h3>Teacher Information</h3><input type="hidden" name="edit_id" id="t_id"><div class="form-group"><label>Teacher ID</label><input type="text" name="teacher_id" id="t_tid" required></div><div class="form-group"><label>Full Name</label><input type="text" name="full_name" id="t_name" required></div><div class="form-group"><label>Subject Handled</label><input type="text" name="subject_handled" id="t_subj" required></div><div class="form-group"><label>Schedule</label><input type="text" name="schedule" id="t_sched" placeholder="e.g. Mon/Wed 7:30AM" required></div><button type="submit" name="save_teacher" class="btn-save">Save Teacher</button></form></div>
    </div>

    <div id="modalUser" class="modal">
        <div class="modal-content"><form method="POST"><span class="btn-close" onclick="closeModal('modalUser')">Close</span><h3>System Access Account</h3><input type="hidden" name="edit_id" id="u_id"><div class="form-group"><label>Username</label><input type="text" name="username" id="u_user" required></div><div class="form-group"><label>Password</label><input type="password" name="password" id="u_pass"><small style="color:var(--text-gray);">Leave blank if you don't want to change password.</small></div><div class="form-group"><label>Full Name</label><input type="text" name="full_name" id="u_name" required></div><div class="form-group"><label>Role / Position</label><input type="text" name="role" id="u_role" placeholder="e.g. Admin, Registrar, Staff" required></div><div class="form-group"><label>Account Status</label><select name="status" id="u_status"><option value="Active">Active</option><option value="Frozen">Frozen / Locked</option></select></div><button type="submit" name="save_user" class="btn-save">Save Account</button></form></div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) { tabcontent[i].className = tabcontent[i].className.replace(" active", ""); }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
            document.getElementById(tabName).className += " active";
            evt.currentTarget.className += " active";
        }

        function openModal(id, data = null) {
            document.getElementById(id).style.display = "block";
            if(data) {
                if(id === 'modalStudent') {
                    document.getElementById('modalStudentTitle').innerText = "Edit Student";
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('student_id').value = data.student_id;
                    document.getElementById('full_name').value = data.full_name;
                    document.getElementById('course').value = data.course;
                    document.getElementById('status').value = data.status;
                }
                if(id === 'modalTeacher') {
                    document.getElementById('t_id').value = data.id;
                    document.getElementById('t_tid').value = data.teacher_id;
                    document.getElementById('t_name').value = data.full_name;
                    document.getElementById('t_subj').value = data.subject_handled;
                    document.getElementById('t_sched').value = data.schedule;
                }
                if(id === 'modalUser') {
                    document.getElementById('u_id').value = data.id;
                    document.getElementById('u_user').value = data.username;
                    document.getElementById('u_name').value = data.full_name;
                    document.getElementById('u_role').value = data.role;
                    document.getElementById('u_status').value = data.status;
                    document.getElementById('u_pass').required = false;
                }
            } else {
                document.querySelectorAll('#'+id+' input').forEach(input => input.value = '');
                document.querySelectorAll('#'+id+' select').forEach(select => select.selectedIndex = 0);
                if(id === 'modalStudent') document.getElementById('modalStudentTitle').innerText = "Add New Student";
            }
        }

        function closeModal(id) { document.getElementById(id).style.display = "none"; }
        window.onclick = function(event) { if (event.target.classList.contains('modal')) event.target.style.display = "none"; }
    </script>
    <script src="../admin_script.js"></script>
</body>
</html>