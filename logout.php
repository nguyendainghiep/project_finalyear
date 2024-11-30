<?php
// Start the session (if not started already)
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page or home page
header("Location: index.php"); // Change 'index.php' to your home page or login page URL
exit();
?>
