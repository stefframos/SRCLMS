<?php
session_start();
include 'db.php';

if (!isset($_SESSION["member_id"])) {
  header("Location: member_login.php");
  exit;
}

$member_id = $_SESSION["member_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $old_password = $_POST["old_password"];
  $new_password = $_POST["new_password"];
  $confirm_password = $_POST["confirm_password"];

  $sql = "SELECT * FROM members WHERE id=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $member_id, $old_password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    if ($new_password == $confirm_password) {
      $sql = "UPDATE members SET password=? WHERE id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("si", $new_password, $member_id);
      $stmt->execute();

      $success = "Password changed successfully!";
    } else {
      $error = "New password and confirm password do not match";
    }
  } else {
    $error = "Invalid old password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System - Change Password</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <?php include 'navbarmember.php'; ?>
  <main class="changepass-container">
    <h1>Change Password</h1>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="old_password">Old Password:</label>
      <input type="password" id="old_password" name="old_password"><br><br>
      <label for="new_password">New Password:</label>
      <input type="password" id="new_password" name="new_password"><br><br>
      <label for="confirm_password">Confirm Password:</label>
      <input type="password" id="confirm_password" name="confirm_password"><br><br>
      <input type="submit" value="Change Password">
    </form>
  </main>
</body>
</html>