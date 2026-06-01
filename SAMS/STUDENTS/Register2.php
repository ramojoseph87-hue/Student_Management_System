<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Teacher | SAMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Untitled.png" type="image/x-icon">
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
            <img src="Untitled.png" alt="School Logo">
            <h2>SAINT THOMAS AQUINAS <br>COLLEGE</h2>
            <p>Faculty / Teacher Registration</p>
            <!-- ✅ NAKALAGAY ULI MGA DEPT/KURSO PARA TUGMA -->
            <p class="courses">BSIS | BSOA | BSBA | BEEd | BSCRIM</p>
        </div>

        <div class="form-side">
            <div class="Register-Container">
                <h2>Register as Teacher / Faculty</h2>

                <?php if(isset($_SESSION['error'])): ?>
                    <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>

                <form action="Register_Process.php" method="POST">
                    <input type="hidden" name="user_type" value="teacher">

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
                        <div class="form-group">
                            <label>Employee / ID No.:</label>
                            <input type="text" name="id_number" required placeholder="Ex: T-1234">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Department / Assigned Course:</label>
                            <!-- ✅ INAYOS KO NA, TUGMA SA MGA KURSO NIYO -->
                            <select name="department" required>
                                <option value="" disabled selected>-- Select Department --</option>
                                <option value="BSIS - Information System Department">BSIS - Information System Department</option>
                                <option value="BSOA - Office Administration Department">BSOA - Office Administration Department</option>
                                <option value="BSBA - Business Administration Department">BSBA - Business Administration Department</option>
                                <option value="BEEd - Education Department">BEEd - Education Department</option>
                                <option value="BSCRIM - Criminology Department">BSCRIM - Criminology Department</option>
                                <option value="General Education Dept">General Education Department</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Position / Rank:</label>
                            <input type="text" name="position" placeholder="Ex: Instructor I, Professor, Dept. Head">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Subject(s) Handled:</label>
                            <input type="text" name="subjects" placeholder="Ex: Programming, Accounting, Mathematics">
                        </div>
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="email" name="email" required placeholder="faculty@school.edu.ph">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" required placeholder="Create Username">
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" required placeholder="Create Password">
                        </div>
                    </div>

                    <button type="submit">Register Faculty Account</button>
                    <p>Already have an account? <a href="login.php">Back to Login</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- ⚙️ SCRIPT PARA SA DARK MODE -->
    <script>
        const toggle = document.getElementById('darkmode');
        if (localStorage.getItem('darkMode') === 'enabled') {
            toggle.checked = true;
            document.body.classList.add('dark-mode');
        }
        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    </script>

</body>
</html>