<?php
session_start();
include 'db.php';

if (!isset($_SESSION["member_id"])) {
  header("Location: member_login.php");
  exit;
}

$member_id = $_SESSION["member_id"];

// Retrieve the profile picture from the database
$sql = "SELECT profile_picture FROM members WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$profile_picture_url = "uploads/" . $row["profile_picture"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $profile_picture = $_FILES["profile_picture"];

  $allowed_extensions = array("jpg", "jpeg", "png", "gif");
  $extension = pathinfo($profile_picture["name"], PATHINFO_EXTENSION);

  if (in_array($extension, $allowed_extensions)) {
    $new_name = uniqid() . "." . $extension;
    $upload_path = "uploads/" . $new_name;

    if (move_uploaded_file($profile_picture["tmp_name"], $upload_path)) {
      $sql = "UPDATE members SET profile_picture=? WHERE id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("si", $new_name, $member_id);
      $stmt->execute();

      $success = "Profile picture changed successfully!";
    } else {
      $error = "Failed to upload profile picture";
    }
  } else {
    $error = "Invalid file type";
  }
}

if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}
if (!is_writable('uploads')) {
    $error = "Uploads directory is not writable";
}
?>

<!-- Change profile picture form HTML here -->

<html>
<head>
  <title>Library Management System - Change Profile Picture</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <h1>Library Management System - Change Profile Picture</h1>
  <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
  <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
  
  <!-- Display the profile picture -->
  <img src="<?php echo $profile_picture_url; ?>" alt="Profile Picture" width="100" height="100"><br><br>
  
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <label for="profile_picture">Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture"><br><br>
    <input type="submit" value="Change Profile Picture">
  </form>
  
</body>
</html>