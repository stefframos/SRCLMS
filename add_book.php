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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = $_POST["title"];
    $category = $_POST["category"];
    $author = $_POST["author"];
    $copies = $_POST["copies"];
    $book_pub = $_POST["book_pub"];
    $publisher_name = $_POST["publisher_name"];
    $isbn = $_POST["isbn"];
    $copyright_year = $_POST["copyright_year"];
    $date_added = date("Y-m-d H:i:s"); // Set the current date and time

    // Insert the book into the database
    $sql = "INSERT INTO books (title, category, author, copies, book_pub, publisher_name, isbn, copyright_year, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $title, $category, $author, $copies, $book_pub, $publisher_name, $isbn, $copyright_year, $date_added);
    if ($stmt->execute()) {
        header("Location: books.php"); // Redirect to the index page
        exit;
    } else {
        $error = "Failed to add book: " . $conn->error;
    }
}

// Display the form
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<main class="container">
<h2 class="mt-4">Add Book</h2>
    
</body>
</html>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="title">Book Title:</label>
    <input type="text" id="title" name="title" required><br><br>
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br><br>
    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br><br>
    <label for="copies">Copies:</label>
    <input type="number" id="copies" name="copies" required><br><br>
    <label for="book_pub">Book Publication:</label>
    <input type="text" id="book_pub" name="book_pub" required><br><br>
    <label for="publisher_name">Publisher Name:</label>
    <input type="text" id="publisher_name" name="publisher_name" required><br><br>
    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn" required><br><br>
    <label for="copyright_year">Copyright Year:</label>
    <input type="number" id="copyright_year" name="copyright_year" required><br><br>
    <input type="submit" value="Add Book">
</form>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>