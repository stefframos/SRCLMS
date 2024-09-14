<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Verify username and password
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $hashed_password = $user_data["password"];

            // Hash the input password and compare it with the stored hash
            if (password_verify($password, $hashed_password)) {
                // Login successful, set session variable
                $_SESSION["username"] = $username;
                header("Location: dashboard.php");
                die("Redirecting to dashboard...");
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!-- Login form HTML here -->

<html>
<head>
    <title>Library Management System - Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<main class="admincontainer">
<div class="adminlog-form">
    <h1>Admin Login</h1>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Login">
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
        <p><a href="forgot_password.php">Forgot your password?</a></p>
    </form>
</main>
</div>
</body>
</html>