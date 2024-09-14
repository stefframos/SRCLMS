<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    // Check if username is not empty
    if (empty($username)) {
        $error = "Please enter your username";
    } else {
        // Verify username
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a random password reset token
            $token = bin2hex(random_bytes(16));

            // Update the user's password reset token
            $sql = "UPDATE users SET password_reset_token=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $token, $username);
            $stmt->execute();

            // Send a password reset email to the user
            $to = $username;
            $subject = "Password Reset Request";
            $body = "Click this link to reset your password: <a href='reset_password.php?token=$token'>Reset Password</a>";
            mail($to, $subject, $body);

            $success = "Password reset email sent successfully!";
        } else {
            $error = "Invalid username";
        }
    }
}
?>
<!-- Forgot password form HTML here -->

<html>
<head>
    <title>Library Management System - Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h1>Library Management System - Forgot Password</h1>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>