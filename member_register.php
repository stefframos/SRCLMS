<?php
// Include the database connection file
require_once 'db.php';
require_once 'C:/xampp/htdocs/LMSfiles/BLOCK/qrcode/phpqrcode/qrlib.php';

// Initialize variables
$name = '';
$username = '';
$email = '';
$student_id = '';
$password = '';
$confirm_password = '';
$error = ''; // Initialize error variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['retype_password'];

    // Validate the student ID number
    $sql = "SELECT id FROM members WHERE student_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Student ID number is already registered. Please try a different one.";
    }

    // Validate the username
    $sql = "SELECT id FROM members WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Username is already taken. Please choose a different one.";
    }

    // Check if the passwords match
    if ($password != $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    }

    // Check if the password is not empty
    if (empty($password)) {
        $error = "Password cannot be empty. Please enter a password.";
    }

   // Insert the new member into the database
   if (empty($error)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO members (name, username, email, student_id, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $name, $username, $email, $student_id, $hashed_password);
    $stmt->execute();
    $member_id = $stmt->insert_id; // Get the newly inserted member ID

    // Generate a unique QR code for the member
    $path = "images/";
    if (!is_dir($path)) {
        mkdir($path);
    }
    $file = uniqid().'.png';
    $full_path = $path.$file;
    $text = "Name: $name\nStudent ID: $student_id";
    QRcode::png($text, $full_path, 'H', 5, 5);

    // Update the member record with the generated QR code file name
    $sql = "UPDATE members SET generated_code = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $file, $member_id);
    $stmt->execute();

  
echo "<div style='display: flex; flex-direction: column; align-items: center;'>";
echo "<img src='$full_path' alt='QR Code' style='width: 300px; height: 300px;'>";
echo "<p>Please take a screenshot of your QR code.</p>";
echo "<p id='countdown'>You will be redirected to the login page in <span id='count'>20</span> seconds.</p>";
echo "<p><a href='member_login.php'>Back to Login</a> or wait to be redirected automatically.</p>";
echo "</div>";

// Hide the completed information
echo "<style>form { display: none; }</style>";

$success = "Registration successful!";
echo "<p style='color: green;'>$success</p>";

// Start the countdown
echo "<script>
      var count = 20;
      var countdownInterval = setInterval(function() {
        document.getElementById('count').innerHTML = count;
        count--;
        if (count <= 0) {
          clearInterval(countdownInterval);
          window.location.href = 'member_login.php';
        }
      }, 1000);
    </script>";
  }
}
?>

<html>
<head>
  <title>Library Management System - Member Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</head>
<main class="memregcontainer">
  
<body class="memreg">
<h1 style="font-family: Arial, sans-serif; font-size: 36px; color: #333;">Register Form</h1>
<p style="font-family: Arial, sans-serif; font-size: 18px; color: #666;">Be a Library Member Today!</p>
 <?php  if (isset($error)) { ?>
    <p style='color: red;'><?php echo $error; ?></p>
  <?php } ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br><br>
    <label for="student_id">Student ID Number:</label>
    <?php if (isset($student_id_error)) { ?>
      <input type="text" id="student_id" name="student_id" value="<?php echo $student_id; ?>" pattern="[0-9]{9}" title="9 digits only" style="border: 1px solid red;">
    <?php } else { ?>
      <input type="text" id="student_id" name="student_id" value="<?php echo $student_id; ?>" pattern="[0-9]{9}" title="9 digits only">
    <?php } ?><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc;"><br><br>
    <label for="username">Username:</label>
    <?php if (isset($username_error)) { ?>
      <input type="text" id="username" name="username" value="<?php echo $username; ?>" style="border: 1px solid red;">
    <?php } else { ?>
      <input type="text" id="username" name="username" value="<?php echo $username; ?>">
    <?php } ?><br><br>
    <label for="password">Password:</label>
    <?php if (isset($password_error)) { ?>
      <input type="password" id="password" name="password" value="<?php echo $password; ?>" style="border: 1px solid red;">
    <?php } else { ?>
      <input type="password" id="password" name="password" value="<?php echo $password; ?>">
    <?php } ?><br><br>
    <label for="retype_password">Retype Password:</label>
    <?php if (isset($password_error)) { ?>
      <input type="password" id="retype_password" name="retype_password" value="<?php echo $confirm_password; ?>" style="border: 1px solid red;">
    <?php } else { ?>
      <input type="password" id="retype_password" name="retype_password" value="<?php echo $confirm_password; ?>">
    <?php } ?><br><br>
    <input type="submit" value="Register">
    <p>Already have an account? <a href="member_login.php">Login here</a></p>
  </form>
  </form>
</body>
</html>