<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["login"])) {
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);


    $sql = "SELECT * FROM members WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $member_data = $result->fetch_assoc();
      $hashed_password = $member_data["password"];
  

      if (password_verify($password, $hashed_password)) {
        $_SESSION["member_id"] = $member_data["id"];
        $_SESSION["username"] = $member_data["username"];
        header("Location: member_home.php");
        exit;
      } else {
        $error = "Invalid username or password";
      }
    } else {
      $error = "Invalid username or password";
    }
  } elseif (isset($_POST["register"])) {
    // ...
  }
}
?>

<!-- Login and registration form HTML here -->

<html>
<head>
  <title>Library Management System - Member Login</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Add this line for mobile responsiveness -->
</head>
<body class="member-body">
<main class="membercontainer">
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="logo-container">
    <img src="logo.png" alt="Library Logo" class="member-logo"> <!-- Add logo image -->
    <memberh1 class="memberh1">Welcome SRCians!</memberh1>
  </div>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username"><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password"><br><br>
      <input type="submit" name="login" value="Login" class="btn"> <!-- Add a class for styling -->
    </form>
    <style>
      a {
        color:blue;
      }
      </style>
    <p>Don't have an account? <a href="member_register.php">Register here</a></p>
  </main>
</body>
</html>