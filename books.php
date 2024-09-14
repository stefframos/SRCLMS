<?php
// Include the database connection file
include 'db.php';

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

// Read all books
$sql = "SELECT * FROM books";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) { // Check if the query was successful
        $books = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $error = "Failed to retrieve books: " . $conn->error;
        $books = array(); // Initialize an empty array
    }
} else {
    $error = "Failed to prepare statement: " . $conn->error;
    $books = array(); // Initialize an empty array
}

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) { // Check if the query was successful
        $books = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $error = "Failed to retrieve books: " . $conn->error;
        $books = array(); // Initialize an empty array
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <main class="container">
        <a href="add_book.php" class="btn btn-primary">Add Book</a> 
        <a href="borrow_book.php" class="btn btn-primary">Borrow book</a> 
        <h2 class="mt-4">Books</h2>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Copies</th>
                    <th>Publication Date</th>
                    <th>Publisher</th>
                    <th>ISBN</th>
                    <th>Copyright Year</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)) { ?>
                    <?php foreach ($books as $book) { ?>
                        <tr>
                            <td><?php echo $book["id"]; ?></td>
                            <td><?php echo $book["title"]; ?></td>
                            <td><?php echo $book["category"]; ?></td>
                            <td><?php echo $book["author"]; ?></td>
                            <td><?php echo $book["copies"]; ?></td>
                            <td><?php echo $book["book_pub"]; ?></td>
                            <td><?php echo $book["publisher_name"]; ?></td>
                            <td><?php echo $book["isbn"]; ?></td>
                            <td><?php echo $book["copyright_year"]; ?></td>
                            <td><?php echo $book["date_added"]; ?></td>
                            <td>
                                <a href="edit_book.php?id=<?php echo $book['id']; ?>">Edit</a>
                                <a href="delete_book.php?id=<?php echo $book["id"]; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="11">You haven't added any books yet. <a href="add_book.php">Click here to add a new book!</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true') { ?>
            <div class="alert alert-success">Book successfully edited!</div>
        <?php } ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>