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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<main class="container">
    <a href="books.php" class="btn btn-primary">Back to Books</a> 
    <h2>Borrow Book</h2>
    <form method="post" action="borrow_book_form.php" onsubmit="return validateForm()">
    ...
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
                    <th>Select</th>
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
                                <input type="checkbox" name="book_id[]" value="<?php echo $book["id"]; ?>">
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="11">No books available for borrowing.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Borrow</button>
    </form>

    <script>
function validateForm() {
    var checkboxes = document.getElementsByName("book_id[]");
    var checked = false;
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checked = true;
            break;
        }
    }
    if (!checked) {
        alert("Please select at least one book to borrow.");
        return false;
    }
    return true;
}
</script>


    <?php if (isset($error)) { echo "Error: " . $error . "<br>"; } ?>
    <?php if (isset($books)) { echo "Number of books retrieved: " . count($books) . "<br>"; } ?>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>