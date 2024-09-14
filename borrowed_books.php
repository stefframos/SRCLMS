<?php
// Include the database connection file
require_once 'db.php';
include 'header.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];

// Read all borrowed books
$sql = "SELECT borrowed_books.*, members.student_id FROM borrowed_books INNER JOIN members ON borrowed_books.member_id = members.id";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $borrowed_books = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}

// Get the member's name from the database
function get_member_name($member_id) {
    global $conn; // Add this line to access the $conn object
    $sql = "SELECT name FROM members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Check if the statement is successfully prepared
        $stmt->bind_param("i", $member_id); // Bind the member ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["name"];
    } else {
        $error = "Failed to prepare statement: " . $conn->error;
    }
}

// Get the book's title from the database
function get_book_title($book_id) {
    global $conn; // Add this line to access the $conn object
    $sql = "SELECT title FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Check if the statement is successfully prepared
        $stmt->bind_param("i", $book_id); // Bind the book ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["title"];
    } else {
        $error = "Failed to prepare statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Books</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
</head>
<body>
    <main class="container">
        <h2 class="mt-4">Borrowed Books</h2>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Member Name</th>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($borrowed_books)) { ?>
        <?php foreach ($borrowed_books as $borrowed_book) { ?>
            <tr>
                <td><?php echo $borrowed_book["student_id"]; ?></td>
                <td><?php echo !empty($borrowed_book["member_id"]) ? get_member_name($borrowed_book["member_id"]) : ''; ?></td>
                <td><?php echo $borrowed_book["book_id"]; ?></td>
                <td><?php echo !empty($borrowed_book["book_id"]) ? get_book_title($borrowed_book["book_id"]) : ''; ?></td>
                <td><?php echo $borrowed_book["borrow_date"]; ?></td>
                <td><?php echo $borrowed_book["return_date"]; ?></td>
                <td><?php echo $borrowed_book["status"]; ?></td>
                <td>
                    <?php if ($borrowed_book["status"] != "Returned") { ?>
                        <a href="return_book.php?book_id=<?php echo $borrowed_book["book_id"]; ?>&member_id=<?php echo $borrowed_book["member_id"]; ?>">Return</a>
                    <?php } else { ?>
                        <p>Already Returned</p>
                    <?php } ?>
                    <a href="delete_transaction.php?book_id=<?php echo $borrowed_book["book_id"]; ?>&member_id=<?php echo $borrowed_book["member_id"]; ?>" onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
</tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('#example').DataTable();
            });
        </script>
    </main>
</body>
</html>