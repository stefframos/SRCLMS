<?php
include 'db.php';

// Initialize the stored values
$stored_name = '';
$stored_username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $retype_password = $_POST["retype_password"];

    // Store the input values in variables to preserve them on error
    $stored_name = $name;
    $stored_username = $username;

    // Check if all input fields are filled in
    if (empty($name) || empty($username) || empty($password) || empty($retype_password)) {
        $error = "Please fill in all fields";
    } else {
        // Check if username is at least 4 characters long
        if (strlen($username) < 4) {
            $error = "Username must be at least 4 characters long";
        } else {
            // Check if username is already taken
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = "Username is already taken";
            } else {
                // Check if passwords match
                if ($password != $retype_password) {
                    $error = "Passwords do not match";
                } else {
                    // Check if password is at least 6 characters long
                    if (strlen($password) < 6) {
                        $error = "Password must be at least 6 characters long";
                    } else {
                        // Hash the password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Insert user into database
                        $sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sss", $name, $username, $hashed_password);
                        $stmt->execute();

                        // Set success message
                        $success = "Sign up successful! You can now log in.";

                        // Redirect to login page
                        header("Location: login.php");
                        die("Redirecting to login...");
                    }
                }
            }
        }
    }
}
?>

<html>
<head>
    <title>Library Management System - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<main class="container">
    <h1>Library Management System - Sign Up</h1>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $stored_name; ?>"><br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $stored_username; ?>"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <label for="retype_password">Retype Password:</label>
        <input type="password" id="retype_password" name="retype_password"><br><br>
        <input type="submit" value="Signup">
        <a href="login.php">Already have an account?</a>
    </form>
</main>
</body>
</html>