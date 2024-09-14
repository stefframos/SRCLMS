<?php
session_start();
include 'db.php';

if (!isset($_SESSION["member_id"])) {
    header("Location: member_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST["search_term"];

    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term_esc = $conn->real_escape_string($search_term);
    $search_term1 = "%$search_term_esc%";
    $search_term2 = "%$search_term_esc%";
    $stmt->bind_param("ss", $search_term1, $search_term2);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = array();

    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLeSaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
</head>
<body>
    <?php include 'navbarmember.php'; ?>
   
    <main class="container">
        <h2 class="mt-4">Search Books</h2>
        <form action="search_books.php" method="post">
            <div class="form-group">
                <label for="search_term">Search Term</label>
                <input type="text" class="form-control" id="search_term" name="search_term" value="<?php echo isset($search_term) ? htmlspecialchars($search_term) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <?php if (isset($books) && !empty($books)): ?>
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                    
                            <td><?php echo $book["title"]; ?></td>
                            <td><?php echo $book["author"]; ?></td>
                          
                            <td>
                                <?php if ($book["copies"] > 0): ?>
                                    Available
                                <?php else: ?>
                                    Not Available
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('#example').DataTable();
                });
            </script>
        <?php endif; ?>
    </main>
          
</body>
</html>