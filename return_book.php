<?php
// Include the database connection file
require_once 'db.php';

// Get the book ID and member ID from the URL parameters
$book_id = $_GET["book_id"];
$member_id = $_GET["member_id"];

// Display a confirmation message
echo "<h2>Are you sure you want to return the book?</h2>";
echo "<p>Book ID: $book_id, Member ID: $member_id</p>";
echo "<a href='return_book.php?book_id=$book_id&member_id=$member_id&confirm=yes'>Yes</a> | <a href='borrowed_books.php'>No</a>";

// Check if the user has confirmed
if (isset($_GET["confirm"]) && $_GET["confirm"] == "yes") {
    // Update the status of the borrowed book to "Returned"
    $sql = "UPDATE borrowed_books SET status = 'Returned' WHERE book_id = '$book_id' AND member_id = '$member_id'";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Check if the statement is successfully prepared
        $stmt->execute();

        // Insert the returned book into the returned_books table
        $sql = "INSERT INTO returned_books (book_id, member_id, return_date) VALUES ('$book_id', '$member_id', NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt) { // Check if the statement is successfully prepared
            $stmt->execute();
            header("Location: borrowed_books.php");
            exit;
        } else {
            $error = "Failed to prepare statement: " . $conn->error;
        }
    } else {
        $error = "Failed to prepare statement: " . $conn->error;
    }
}
?>