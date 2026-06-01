<?php
session_start();
session_unset();    // Tanggalin lahat ng naka-save na data
session_destroy();  // Isara ang session

// ❗ TAMA ANG DAAN: Babalik sa login na nasa STUDENTS folder
header("Location: login.php");
exit;
?>