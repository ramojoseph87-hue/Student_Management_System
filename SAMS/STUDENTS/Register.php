<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SAMS</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../Untitled.png" type="image/x-icon">
</head>
<body>

<div class="mode-switch">
    <span>☀️</span>
    <label class="switch">
        <input type="checkbox" id="darkmode">
        <span class="slider"></span>
    </label>
    <span>🌙</span>
</div>

<div class="main-wrapper">
    <div class="school-side">
        <img src="../Untitled.png" alt="Saint Thomas Aquinas College">
        <h2>SAINT THOMAS AQUINAS <br>COLLEGE</h2>
        <p>Create New Account</p>
    </div>

    <div class="form-side">
        <div class="Register-Container">
            <h2>Register Account</h2>

            <?php if(isset($_SESSION['error'])): ?>
                <p style="color:red; text-align:center; padding:8px; background:#ffebee; border-radius:5px;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <?php if(isset($_SESSION['success'])): ?>
                <p style="color:green; text-align:center; padding:8px; background:#e8f5e9; border-radius:5px;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php endif; ?>

<form action="Register_Process.php" method="POST">
    <div class="form-row">
        <div class="form-group" style="width:100%;">
            <label>Account Type:</label>
            <select name="user_type" id="user_type" class="form-control" required onchange="showFields()">
                <option value="">-- Select Account Type --</option>
                <option value="Student">Student</option>
                <option value="Teacher">Teacher / Faculty</option>
                <option value="Admin">System Administrator</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="firstname" required placeholder="Enter First Name">
        </div>
        <div class="form-group">
            <label>Middle Name:</label>
            <input type="text" name="middlename" placeholder="Optional">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="lastname" required placeholder="Enter Last Name">
        </div>
        <div class="form-group" id="student_id_field">
            <label>Student ID No.:</label>
            <input type="text" name="id_number" placeholder="Ex: 2023-00123">
        </div>
    </div>

    <!-- 🎓 STUDENT FIELDS - EKSAKTO SA GUSTO MO -->
    <div id="student_fields">
        <div class="form-row">
            <!-- ✅ COURSE / PROGRAM: PINAKAMALAPAD - MAKIKITA ANG BUO -->
            <div class="form-group" style="flex: 4.5;">
                <label>Course / Program:</label>
                <select name="course" id="student_course">
                    <option value="" disabled selected>-- Select Course --</option>
                    <option value="BSIS">BSIS - Bachelor of Science in Information Systems</option>
                    <option value="BSOA">BSOA - Bachelor of Science in Office Administration</option>
                    <option value="BSBA">BSBA - Bachelor of Science in Business Administration</option>
                    <option value="BEEd">BEEd - Bachelor of Elementary Education</option>
                    <option value="BSCRIM">BSCRIM - Bachelor of Science in Criminology</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <!-- ✅ YEAR LEVEL & SECTION: IPAGKATABI NA SILA -->
            <div class="form-group" style="flex: 1;">
                <label>Year Level:</label>
                <select name="year_level">
                    <option value="" disabled selected>-- Year --</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Section / Block:</label>
                <input type="text" name="section" placeholder="Ex: A, B, 1-A">
            </div>
        </div>
    </div>

    <!-- 👨‍🏫 TEACHER & ADMIN FIELDS -->
    <div id="teacher_admin_fields" style="display:none;">
        <div class="form-row">
            <!-- ✅ DEPT / COURSE: SOBRANG LAPAD DIN -->
            <div class="form-group" style="flex: 4.5;">
                <label>Department / Assigned Course:</label>
                <select name="dept" id="teacher_course">
                    <option value="" disabled selected>-- Select Department / Course --</option>
                    <option value="BSIS">BSIS - Bachelor of Science in Information Systems</option>
                    <option value="BSOA">BSOA - Bachelor of Science in Office Administration</option>
                    <option value="BSBA">BSBA - Bachelor of Science in Business Administration</option>
                    <option value="BEEd">BEEd - Bachelor of Elementary Education</option>
                    <option value="BSCRIM">BSCRIM - Bachelor of Science in Criminology</option>
                    <option value="Administration">School Administration / Office</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group" style="flex: 1.5;" id="subject_field">
                <label>Subject / Specialization:</label>
                <input type="text" name="subject_handled" placeholder="Ex: Programming, Math">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required placeholder="Enter Email Address">
        </div>
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required placeholder="Create Username">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="width:100%;">
            <label>Password:</label>
            <input type="password" name="password" required placeholder="Create Strong Password">
        </div>
    </div>

    <button type="submit" style="width:100%; margin-top:15px; padding:10px; font-size:16px;">✅ REGISTER ACCOUNT</button>
    <p style="text-align:center; margin-top:10px;">Already have an account? <a href="login.php">Back to Login</a></p>
</form>

<script>
function showFields() {
    const type = document.getElementById('user_type').value;
    document.getElementById('student_fields').style.display = (type === 'Student') ? 'block' : 'none';
    document.getElementById('student_id_field').style.display = (type === 'Student') ? 'block' : 'none';
    document.getElementById('teacher_admin_fields').style.display = (type === 'Teacher' || type === 'Admin') ? 'block' : 'none';
    document.getElementById('subject_field').style.display = (type === 'Teacher') ? 'block' : 'none';

    if(type === 'Student'){
        document.querySelector('select[name="course"]').required = true;
        document.querySelector('select[name="year_level"]').required = true;
        document.querySelector('input[name="section"]').required = true;
        document.querySelector('input[name="id_number"]').required = true;
    } else {
        document.querySelector('select[name="course"]').required = false;
        document.querySelector('select[name="year_level"]').required = false;
        document.querySelector('input[name="section"]').required = false;
        document.querySelector('input[name="id_number"]').required = false;
    }
}

const toggle = document.getElementById('darkmode');
if(localStorage.getItem('darkMode') === 'enabled') {
    toggle.checked = true;
    document.body.classList.add('dark-mode');
}
toggle.addEventListener('change', () => {
    toggle.checked ? document.body.classList.add('dark-mode') : document.body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', toggle.checked ? 'enabled' : 'disabled');
});
</script>

</body>
</html>