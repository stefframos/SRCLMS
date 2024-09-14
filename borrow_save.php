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

// Get the member ID from the form
$member_id = $_POST["member_id"];

// Get the member's name from the database
$sql = "SELECT name FROM members WHERE id = '$member_id'";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $member_name = $result->fetch_assoc()["name"];
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}

// Create a database for borrowed books if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS borrowed_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT,
    book_id INT,
    borrow_date DATE,
    return_date DATE,
    status VARCHAR(255)
)";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}

// Insert the borrowed book into the database
$book_ids = explode(',', $_POST["book_ids"]);
$member_id = $_POST["member_id"];

if (!empty($book_ids)) {
    foreach ($book_ids as $book_id) {
        $sql = "INSERT INTO borrowed_books (member_id, book_id, borrow_date, return_date, status) VALUES ('$member_id', '$book_id', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'Borrowed')";
        $stmt = $conn->prepare($sql);
        if ($stmt) { // Check if the statement is successfully prepared
            $stmt->execute();
            $message = "Book successfully issued to: $member_name";
        } else {
            $error = "Failed to prepare statement: " . $conn->error;
        }
    }
} else {
    $error = "No book IDs selected";
}

// Display the message
echo "<script>alert('$message');</script>";

// Redirect to borrowed_books.php
echo "<script>window.location.href='borrowed_books.php';</script>";