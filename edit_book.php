<?php
session_start();
// Include the database connection file
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['id']; // Get the book ID from the POST parameter
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $copies = $_POST['copies'];
    $book_pub = $_POST['book_pub'];
    $publisher_name = $_POST['publisher_name'];
    $isbn = $_POST['isbn'];
    $copyright_year = $_POST['copyright_year'];

    // Read the book details from the database
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    // Check if the book exists
    if (!$book) {
        echo "Error: Book not found!";
        exit;
    }

    // Update the book details
    $sql = "UPDATE books SET title = ?, category = ?, author = ?, copies = ?, book_pub = ?, publisher_name = ?, isbn = ?, copyright_year = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $title, $category, $author, $copies, $book_pub, $publisher_name, $isbn, $copyright_year, $book_id);
    if (!$stmt->execute()) {
        $error = "Error updating book: " . $stmt->error;
        echo "Update failed: " . $stmt->error; // Display the error message
        echo "<br>SQL: " . $sql; // Display the SQL query
        echo "<br>Params: "; // Display the parameters
        print_r(array($title, $category, $author, $copies, $book_pub, $publisher_name, $isbn, $copyright_year, $book_id));
        echo "<br>Script stopping here..."; // Add this line to see if the script is really stopping
        exit; // Stop the script from running
        die("Update failed"); // Add this line to ensure the script is really stopping
    } else {
        $success = "Book updated successfully!";
        header("Location: books.php"); // Redirect to books.php after update
        exit;
    }
    $stmt->close(); // Close the prepared statement
} else {
    // Get the book ID from the GET parameter
    $book_id = $_GET['id'];

    // Read the book details from the database
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    // Check if the book exists
    if (!$book) {
        echo "Error: Book not found!";
        exit;
    }
}


?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<main class="container">
    <h1>Edit Book</h1>
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } elseif (isset($success)) { ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php } ?>
    <form action="edit_book.php" method="post" onsubmit="return updateBook()">
    <input type="hidden" name="id" value="<?php echo $book_id; ?>">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo $book['title']; ?>">
    </div>
    <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" class="form-control" id="category" name="category" value="<?php echo $book['category']; ?>">
    </div>
    <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" class="form-control" id="author" name="author" value="<?php echo $book['author']; ?>">
    </div>
    <div class="form-group">
        <label for="copies">Copies:</label>
        <input type="number" class="form-control" id="copies" name="copies" value="<?php echo $book['copies']; ?>">
    </div>
    <div class="form-group">
        <label for="book_pub">Publication Date:</label>
        <input type="date" class="form-control" id="book_pub" name="book_pub" value="<?php echo $book['book_pub']; ?>">
    </div>
    <div class="form-group">
        <label for="publisher_name">Publisher:</label>
        <input type="text" class="form-control" id="publisher_name" name="publisher_name" value="<?php echo $book['publisher_name']; ?>">
    </div>
    <div class="form-group">
        <label for="isbn">ISBN:</label>
        <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>">
    </div>
    <div class="form-group">
        <label for="copyright_year">Copyright Year:</label>
        <input type="number" class="form-control" id="copyright_year" name="copyright_year" value="<?php echo $book['copyright_year']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Book</button>
    <a href="books.php" class="btn btn-secondary">Back</a>
</form>

<script>
function updateBook() {
    alert("Book updated successfully!");
    return true;
}
</script>
</body>
</html>