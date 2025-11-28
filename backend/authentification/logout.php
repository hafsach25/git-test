<?php


// Destroy the session
session_destroy();

// Clear session variables
$_SESSION = array();

// Redirect to home page
header("Location: ../../BEEX/frontend/authentification/login.php");
exit();
?>