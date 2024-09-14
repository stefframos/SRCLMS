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

// Read all members
$sql = "SELECT * FROM members";
$stmt = $conn->prepare($sql);
if ($stmt) { // Check if the statement is successfully prepared
    $stmt->execute();
    $result = $stmt->get_result();
    $members = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Failed to prepare statement: " . $conn->error;
    $members = array(); // Initialize an empty array to avoid warnings
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>
    <main class="container">
        <h2 class="mt-4">Students</h2>
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)) { // Check if $members is not empty
                    foreach ($members as $member) { ?>
                    <tr>
                        <td><?php echo $member["student_id"]; ?></td>
                        <td><?php echo $member["name"]; ?></td>
                        <td><?php echo $member["email"]; ?></td>
                        <td><?php echo $member["username"]; ?></td>
                        <td>
                            <a href="edit_member.php?id=<?php echo $member["id"]; ?>">Edit</a>
                            <a href="delete_member.php?id=<?php echo $member["id"]; ?>">Delete</a>
                        </td>
                    </tr>
                <?php }
                } else { ?>
                    <tr>
                        <td colspan="5">No members found.</td>
                    </tr>
                <?php } ?>
            </tbody>
            <button type="button" class="btn btn-primary" onclick="printTable()">Print</button>
            
        </table>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
      <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function printTable() {
            var table = document.getElementById('example');
            var win = window.open('', '', 'height=700,width=700');
            win.document.write('<html><head>');
            win.document.write('<title>Printable Table</title>');
            win.document.write('<style>body { font-family: Arial, sans-serif; }</style>');
            win.document.write('</head><body>');
            win.document.write(table.outerHTML);
            win.document.write('</body></html>');
            win.print();
            win.close();
        }
    </script>
    

</body>
</html>