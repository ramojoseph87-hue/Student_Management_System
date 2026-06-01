<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ KUNIN ANG MGA DETALYE MULA SA FORM
    $firstname  = mysqli_real_escape_string($conn, trim($_POST['firstname']));
    $middlename = mysqli_real_escape_string($conn, trim($_POST['middlename'] ?? ''));
    $lastname   = mysqli_real_escape_string($conn, trim($_POST['lastname']));
    $email      = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username   = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type  = mysqli_real_escape_string($conn, trim($_POST['user_type']));

    // ✅ ITO ANG IDEYA MO: BUBUIN NA NATIN AGAD ANG BUONG PANGALAN DITO PA LANG
    // Pinagsama-sama: First Name + Middle Name + Last Name
    $fullname_raw = $firstname . " " . $middlename . " " . $lastname;
    // Linisin natin para mawala ang sobrang puwang kung sakaling walang middle name
    $fullname_clean = trim(preg_replace('/\s+/', ' ', $fullname_raw));
    $fullname   = mysqli_real_escape_string($conn, $fullname_clean);

    // ✅ I-INITIALIZE ANG IBANG DETALYE DEPENDE KUNG ANONG USER TYPE
    $student_id      = "";
    $course          = "";
    $year_level      = "";
    $section         = "";
    $department      = "";
    $subject_handled = "";

    if($user_type == 'Student'){
        $student_id = mysqli_real_escape_string($conn, trim($_POST['id_number']));
        $course     = mysqli_real_escape_string($conn, trim($_POST['course']));
        $year_level = mysqli_real_escape_string($conn, trim($_POST['year_level']));
        $section    = mysqli_real_escape_string($conn, trim($_POST['section']));
    } 
    elseif($user_type == 'Teacher'){
        // Gagawa tayo ng unique ID para sa Teacher kung wala silang Student ID
        $student_id      = "FAC-" . time(); 
        $department      = mysqli_real_escape_string($conn, trim($_POST['dept']));
        $subject_handled = mysqli_real_escape_string($conn, trim($_POST['subject_handled']));
        $course          = "FACULTY"; // Default value
    } 
    elseif($user_type == 'Admin'){
        // Gagawa tayo ng unique ID para sa Admin
        $student_id = "ADM-" . time();
        $department = mysqli_real_escape_string($conn, trim($_POST['dept'] ?? ''));
        $course     = "ADMINISTRATOR"; // Default value
    }

    // ✅ I-CHECK KUNG MAY GAMIT NA ANG USERNAME, EMAIL, O ID NUMBER
    $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR student_id = ?");
    $check->bind_param("sss", $username, $email, $student_id);
    $check->execute();
    
    if($check->get_result()->num_rows > 0){
        $_SESSION['error'] = "❌ Username, Email, or ID Number already exists! Try another.";
        header("Location: Register.php");
        exit;
    }

    // ✅ IPASOK SA DATABASE - SIGURADONG NASA LOOB NA ANG FULLNAME
    // TAMA ANG PAGKAKASUNOD-SUNOD NG MGA COLUMN AT VALUES
    $sql = $conn->prepare("INSERT INTO users (
        student_id, firstname, middlename, lastname, fullname, course, year_level, 
        section, department, subject_handled, email, username, password, user_type
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $sql->bind_param(
        "ssssssssssssss", 
        $student_id, $firstname, $middlename, $lastname, $fullname, $course, 
        $year_level, $section, $department, $subject_handled, $email, $username, 
        $password, $user_type
    );

    // ✅ KUNG SUCCESS, PUPUNTA SA LOGIN
    if($sql->execute()){
        $_SESSION['success'] = "✅ Account created successfully! You can login now.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "❌ Registration Failed! Error: " . $conn->error;
        header("Location: Register.php");
        exit;
    }
}
?>