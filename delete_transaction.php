<?php
// Include the database connection file
require_once 'db.php';

// Get the book ID and member ID from the URL parameters
$book_id = $_GET["book_id"];
$member_id = $_GET["member_id"];

// Delete the transaction from the borrowed_books table
$sql = "DELETE FROM borrowed_books WHERE book_id = '$book_id' AND member_id = '$member_id'";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    header("Location: borrowed_books.php");
    exit;
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}
?>