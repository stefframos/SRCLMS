<?php
// Include the database connection file
require_once 'db.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: member_login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];

// Get the member's ID from the database
$sql = "SELECT id FROM members WHERE username = '$username'";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $member_id = $result->fetch_assoc()["id"];
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}

// Read all borrowed books for the current member
$sql = "SELECT * FROM borrowed_books WHERE member_id = '$member_id'";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $borrowed_books = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - My Borrowed Books</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
</head>
<body>
    <?php include 'navbarmember.php'; ?>
    <main class="return-book-container">
        <h1>My Borrowed Books</h1>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($borrowed_books)) { ?>
                    <?php foreach ($borrowed_books as $borrowed_book) { ?>
                        <tr>
                            <td><?php echo $borrowed_book["book_id"]; ?></td>
                            <td><?php echo get_book_title($borrowed_book["book_id"]); ?></td>
                            <td><?php echo $borrowed_book["borrow_date"]; ?></td>
                            <td><?php echo $borrowed_book["status"]; ?></td>
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

<?php
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