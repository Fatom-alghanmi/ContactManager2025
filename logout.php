<?php
session_start(); 

$_SESSION = []; 
session_destroy(); // ✅ Ends the session and clears session data

$url = "login_form.php";
header("Location: " . $url);
die();
?>
