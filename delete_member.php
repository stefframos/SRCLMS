<?php
// Include the database connection file
include 'db.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];

// Get the member ID from the URL parameter
$member_id = $_GET["id"];

// Delete the member
$sql = "DELETE FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();

header("Location: dashboard.php");
exit;
?>