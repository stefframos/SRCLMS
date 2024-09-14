<?php
// Include the database connection file
include 'db.php';

// Get the member ID from the URL parameter
$id = $_GET["id"];

// Read the member data from the database
$sql = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
?>

<html>
<head>
    <title>Member Update Form</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"> <!-- Add this line -->
</head>
<body>
    <div class="container"> <!-- Add a container div -->
        <h1>Member Update Form</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" onsubmit="return confirm('Are you sure you want to update student details?');">
            <div class="form-group"> <!-- Add a form group container -->
                <input type="hidden" name="id" value="<?php echo $member["id"]; ?>">
                <?php
                $fields = [
                    'student_id' => 'Student ID',
                    'name' => 'Name',
                    'email' => 'Email',
                    'username' => 'Username',
                ];

                foreach ($fields as $field => $label) {
                    ?>
                    <div class="form-field"> <!-- Add a form field container -->
                        <label for="<?php echo $field; ?>"><?php echo $label; ?>:</label>
                        <input type="text" name="<?php echo $field; ?>" value="<?php echo $member[$field]; ?>"><br><br>
                    </div>
                    <?php
                }
                ?>
            </div>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>

<?php
// Update the member data if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $student_id = $_POST["student_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];

    echo "Form submitted with values:<br>";
    echo "ID: $id<br>";
    echo "Student ID: $student_id<br>";
    echo "Name: $name<br>";
    echo "Email: $email<br>";
    echo "Username: $username<br>";

    $sql = "UPDATE members SET student_id = ?, name = ?, email = ?, username = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $student_id, $name, $email, $username, $id);

    if (!$stmt->execute()) {
        echo "Error updating member: " . $stmt->error;
    } else {
        echo "Member updated successfully";
        $stmt->close(); // Close the statement
        $conn->close(); // Close the connection
        header("Location: dashboard.php");
        exit;
    }
}

?>
