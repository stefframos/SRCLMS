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

// Read all returned books
$sql = "SELECT returned_books.*, members.student_id FROM returned_books INNER JOIN members ON returned_books.member_id = members.id";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $returned_books = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}

// Get the member's name from the database
function get_member_name($member_id) {
    global $conn;
    $sql = "SELECT name FROM members WHERE id = '$member_id'";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()["name"];
        } else {
            return 'Deleted member';
        }
    } else {
        return 'Error: ' . $conn->error;
    }
}

// Get the book's title from the database
function get_book_title($book_id) {
    global $conn; // Add this line to access the $conn object
    $sql = "SELECT title FROM books WHERE id = '$book_id'";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Check if the statement is successfully prepared
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["title"];
    } else {
        $error = "Failed to prepare statement: " . $conn->error;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Returned Books</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
</head>
<body>
    <main class="container">
        <h2 class="mt-4">Returned Books</h2>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Student ID</th>
                    <th>Member Name</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($returned_books)) { ?>
                    <?php foreach ($returned_books as $returned_book) { ?>
                        <tr>
                            <td><?php echo $returned_book["book_id"]; ?></td>
                            <td><?php echo get_book_title($returned_book["book_id"]); ?></td>
                            <td><?php echo $returned_book["student_id"]; ?></td>
                            <td><?php echo get_member_name($returned_book["member_id"]); ?></td>
                            <td><?php echo $returned_book["return_date"]; ?></td>
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