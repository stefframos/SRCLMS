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

// Initialize search query
$search_query = "";

// Check if search form is submitted
if (isset($_POST["search"])) {
    $search_query = $_POST["search"];
}

// Read all members based on search query
$sql = "SELECT members.*, members.student_id FROM members WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%' OR username LIKE '%$search_query%'";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $members = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Failed to prepare statement: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book Form</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <main class="container">
        <h2 class="mt-4">Select Member to Borrow Book</h2>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        </form>
        <form method="post" action="borrow_save.php" onsubmit="return validateForm()">

        <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Select</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member) { ?>
            <tr>
                <td><?php echo $member["student_id"]; ?></td>
                <td><?php echo $member["name"]; ?></td>
                <td>
                    <input type="radio" name="member_id" value="<?php echo $member["id"]; ?>">
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="hidden" name="book_ids" value="<?php echo implode(',', $_POST["book_id"]); ?>">
    <button type="submit" class="btn btn-primary">Borrow Book</button>
</form>
    </main>

    <script>
function validateForm() {
    var radios = document.getElementsByName("member_id");
    var checked = false;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            checked = true;
            break;
        }
    }
    if (!checked) {
        alert("Please select a member to borrow the book.");
        return false;
    }
    return true;
}
</script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>