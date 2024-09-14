<?php
session_start();
include 'db.php';

if (!isset($_SESSION["member_id"])) {
  header("Location: member_login.php");
  die("Redirecting to member_login.php"); // Add this line
  exit;
}

$member_id = $_SESSION["member_id"];

$sql = "SELECT name, username, email, generated_code FROM members WHERE id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo "Error preparing SQL statement: " . $conn->error;
  exit;
}
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

$member_data = $result->fetch_assoc();
include 'navbarmember.php'

?>

<!-- Homepage HTML here -->

<html>

<head>
  <title>Library Management System - Member Homepage</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  
  <div class="memhome">
    <header>
      <h1>DASHBOARD</h1>
    </header>
    <section class="memhome info-section">
      <p>Welcome, <?php echo $member_data["name"]; ?>!</p>
      <p>Your Qr Code </P>
      <img src="images/<?php echo $member_data["generated_code"]; ?>" alt="QR Code" class="memhome info-section">
    </section>
    <section class="memhome info-section">
      <h2>Account Information</h2>
      <p>Username: <?php echo $member_data["username"]; ?></p>
      <p>Email: <?php echo $member_data["email"]; ?></p>
    </section>
    <section class="memhome actions-section">
      <h2>Actions</h2>
      <p><a href="change_password.php">Change Password</a></p>
      <p><a href="search_books.php">Search Available Books</a></p>
    </section>
  </div>
  
</body>


</html>