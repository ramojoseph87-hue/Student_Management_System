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

$success = "";
$error = "";

// ✅ PAGPAPADALA NG TICKET / MENSAHE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_ticket'])) {
    $subject = trim($_POST['subject']);
    $category = $_POST['category'];
    $message = trim($_POST['message']);

    if(!empty($subject) && !empty($message)){
        // ✅ DITO NATIN ILALAGAY SA DATABASE PAG MAY TABLE NA
        $success = "✅ Your support ticket has been sent successfully! Our team will respond within 24 hours via your messages or email.";
    } else {
        $error = "❌ Please fill in all required fields (Subject and Message).";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Help & Support | SAMS</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="icon" href="../untitled.png" type="image/x-icon">
    <style>
        /* ✅ CUSTOM STYLE */
        .help-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
            width: 100%;
        }
        .help-card {
            flex: 1 1 320px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .help-card:hover {
            transform: translateY(-3px);
        }
        .help-header {
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            color: white;
            padding: 14px 18px;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .help-body {
            padding: 20px;
        }

        /* ✅ STYLE PARA SA FAQ - ITO ANG GUSTO MONG GALAW */
        .faq-item {
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
            cursor: pointer;
            transition: background 0.2s;
        }
        .faq-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .faq-question {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-answer {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 8px;
            padding-left: 5px;
            line-height: 1.5;
            display: none; /* ✅ DEFAULT: NAKATAGO */
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 14px;
        }
        .contact-icon {
            font-size: 18px;
            color: #2563EB;
            width: 25px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: transparent;
            color: var(--text-color);
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid #10b981;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        .btn-block {
            width: 100%;
            padding: 10px;
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
                <li><a href="assessment_form.php">📄 Assessment Form</a></li> 
                <li><a href="classssched.php">🗓️ Class Schedule</a></li>
                <li><a href="view.php">📝 View Grades</a></li>
                <li><a href="Academic_Records.php">📁 Academic Records</a></li>
                <li><a href="payment_history.php">💵 Payment History</a></li>
                <li><a href="messages.php">📩 Messages</a></li>
                <li><a href="requirements.php">📑 Requirements</a></li>
                <li><a href="Announcements.php">🔔 Announcements</a></li>
                <li><a href="settings.php">⚙️ Settings</a></li>
                <li><a href="help.php" class="active">❓ Help & Support</a></li>
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
                    <h1>❓ Help & Support Center</h1>
                    <p>Find answers, guides, or contact our support team for assistance</p>
                </div>
            </div>

            <?php if(!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if(!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <div class="help-container">

                <!-- ✅ FAQ SECTION - ITO YUNG KINCLICK LALABAS ANG SAGOT -->
                <div class="help-card">
                    <div class="help-header">📌 Frequently Asked Questions</div>
                    <div class="help-body">
                        
                        <!-- ✅ ITEM 1 -->
                        <div class="faq-item" onclick="toggleAnswer(this)">
                            <div class="faq-question">
                                <span>How can I view my grades?</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                You can view your grades by navigating to the <strong>'View Grades'</strong> section from the sidebar menu. Grades are updated by teachers after every grading period (Prelim, Midterm, Semi-Final, and Final).
                            </div>
                        </div>

                        <!-- ✅ ITEM 2 -->
                        <div class="faq-item" onclick="toggleAnswer(this)">
                            <div class="faq-question">
                                <span>Where and how to submit requirements?</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                Go to <strong>'Requirements'</strong> page. Click the blue 📎 Upload button next to the document name. Select your file (PDF or Image/JPG only, max 5MB) and click Submit. Status changes to <span style="color:#eab308">Pending</span> once sent, and <span style="color:#10b981">Completed</span> once verified by the Registrar.
                            </div>
                        </div>

                        <!-- ✅ ITEM 3 -->
                        <div class="faq-item" onclick="toggleAnswer(this)">
                            <div class="faq-question">
                                <span>How do I pay my tuition fees online?</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                Payment options are available under <strong>'Assessment Form'</strong>. We accept GCash, Bank Transfer, and Online Payment. Upload your proof of payment in the <strong>'Payment History'</strong> section for validation.
                            </div>
                        </div>

                        <!-- ✅ ITEM 4 -->
                        <div class="faq-item" onclick="toggleAnswer(this)">
                            <div class="faq-question">
                                <span>Why is my subject missing or not showing?</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                Subjects appear only after your enrollment form is validated and approved by your adviser. If you added subjects but they are missing after 24 hours, please send a message via the <strong>'Messages'</strong> module.
                            </div>
                        </div>

                        <!-- ✅ ITEM 5 -->
                        <div class="faq-item" onclick="toggleAnswer(this)">
                            <div class="faq-question">
                                <span>How to change my password and update info?</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                Go to <strong>'Settings'</strong> page. You can edit your personal details like address and contact number. For security, use the <strong>'Change Password'</strong> section. Make sure to save changes after editing.
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ✅ CONTACT INFO -->
                <div class="help-card">
                    <div class="help-header">📞 Contact Information</div>
                    <div class="help-body">
                        <p style="font-size:14px; margin-bottom:15px; color:var(--text-muted);">
                            Our support team is available Monday to Friday, 8:00 AM - 5:00 PM.
                        </p>

                        <div class="contact-row">
                            <span class="contact-icon">🏢</span>
                            <span>Saint Thomas Aquinas College</span>
                        </div>
                        <div class="contact-row">
                            <span class="contact-icon">📍</span>
                            <span>Main Campus, Cebu City, Philippines</span>
                        </div>
                        <div class="contact-row">
                            <span class="contact-icon">📞</span>
                            <span>(032) 123 4567 | +63 912 345 6789</span>
                        </div>
                        <div class="contact-row">
                            <span class="contact-icon">✉️</span>
                            <span>support@sams.edu.ph</span>
                        </div>
                        <div class="contact-row">
                            <span class="contact-icon">🕒</span>
                            <span>Mon - Fri: 8:00 AM - 5:00 PM</span>
                        </div>
                    </div>
                </div>

                <!-- ✅ SUPPORT TICKET FORM -->
                <div class="help-card">
                    <div class="help-header">📝 Send a Support Ticket</div>
                    <div class="help-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Concern Category</label>
                                <select name="category" required>
                                    <option value="Technical">Technical Issue / System Error</option>
                                    <option value="Grades">Grades & Academic Records</option>
                                    <option value="Payment">Payment & Finance Concerns</option>
                                    <option value="Enrollment">Enrollment & Subjects</option>
                                    <option value="Account">Account & Profile Access</option>
                                    <option value="Other">Other / General Inquiry</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Subject / Title</label>
                                <input type="text" name="subject" placeholder="Short title of your concern" required>
                            </div>

                            <div class="form-group">
                                <label>Message / Description</label>
                                <textarea name="message" placeholder="Describe your problem or question in detail..." required></textarea>
                            </div>

                            <button type="submit" name="send_ticket" class="btn-primary btn-block">📤 Submit Ticket</button>
                        </form>
                    </div>
                </div>

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

        // ✅ ANG TINATANONG MO: ITO ANG FUNCTION NA GUMAGANA KAPAG KINCLICK
        function toggleAnswer(element) {
            const answer = element.querySelector('.faq-answer');
            const arrow = element.querySelector('.arrow');

            if (answer.style.display === "block") {
                // Kung nakabukas, isara ito
                answer.style.display = "none";
                arrow.textContent = "▼";
            } else {
                // Kung nakasara, buksan ito
                answer.style.display = "block";
                arrow.textContent = "▲";
            }
        }

        function confirmLogout() {
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

</body>
</html>