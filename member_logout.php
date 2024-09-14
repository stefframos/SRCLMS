<?php
// Include the database connection file
include 'db.php';

// Start the session
session_start();

// Unset the username session variable
unset($_SESSION["username"]);

// Destroy the session
session_destroy();

// Redirect to the member login page
header("Location: member_login.php");
exit;
?>