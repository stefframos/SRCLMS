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

// Get the book ID from the GET parameter
$book_id = $_GET['id'];

// Check if the book ID is set
if (!isset($book_id) || empty($book_id)) {
    header("Location: books.php");
    exit;
}

// Check if the user has confirmed deletion
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Delete the book from the database
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    if (!$stmt->execute()) {
        $error = "Error deleting book: " . $stmt->error;
        echo "Delete failed: " . $stmt->error; // Display the error message
        echo "<br>SQL: " . $sql; // Display the SQL query
        echo "<br>Params: "; // Display the parameters
        print_r(array($book_id));
        echo "<br>Script stopping here..."; // Add this line to see if the script is really stopping
        exit; // Stop the script from running
        die("Delete failed"); // Add this line to ensure the script is really stopping
    } else {
        $success = "Book deleted successfully!";
        header("Location: books.php?deleted=true"); // Redirect to books.php after deletion
        exit;
    }
    $stmt->close(); // Close the prepared statement
} else {
    // Display a confirmation message before deleting the book
    ?>
    <html>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <body>
        <h2>Delete Book</h2>
        <p>Are you sure you want to delete this book? This action is irreversible.</p>
        <a href="delete_book.php?id=<?php echo $book_id; ?>&confirm=true">Yes, delete the book</a>
        <a href="books.php">No, go back to the book list</a>
    </body>
    </html>
    <?php
    exit;
}
?>