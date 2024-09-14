<?php
session_start();

// Retrieve the attendance records from the database
include 'db.php';
include 'header.php';

// Handle form submission
if(isset($_POST['text'])){
    $text = $_POST['text'];
    $date = date('Y-m-d');
  
    $sql = "SELECT * FROM attendance WHERE student_id='$text' AND LOGDATE = '$date'";
    $query = $conn->query($sql);
    
    if($query ->num_rows > 0){
      $row = $query->fetch_assoc();
      if($row['STATUS'] == '0'){
        // Time out
        $sql = "UPDATE attendance SET TIMEMOUT=NOW(), STATUS='1' WHERE student_id='$text' AND LOGDATE= '$date'";
        $query = $conn->query($sql);
        $_SESSION['success'] = 'Successfuly time out';
      }else{
        // Time in
        $sql = "UPDATE attendance SET TIMEIN=NOW(), STATUS='0' WHERE student_id='$text' AND LOGDATE= '$date'";
        $query = $conn->query($sql);
        $_SESSION['success'] = 'Successfuly time in';
      }
    }else{
      // First time scan, time in
      $sql = "INSERT INTO attendance(student_id,TIMEIN,LOGDATE,STATUS) VALUES ('$text',NOW(),'$date','0')";
      if($conn->query($sql) === TRUE){
        $_SESSION['success'] = 'Successfuly time in';
      }else{
        $_SESSION['error'] = $conn->error ;
      }
    }

   // Authenticate student information
if (isset($_POST['student_id']) && isset($_POST['name'])) {
  $int = $_POST['student_id'];
  $name = $_POST['name'];
  $sql = "SELECT * FROM members WHERE student_id='$int' AND name='$name'";
  $query = $conn->query($sql);
  if($query->num_rows == 0) {
    $_SESSION['error'] = 'NO REGISTERED STUDENT FOUND';
  }
} else {
  $_SESSION['error'] = 'Please fill in all fields';
}

  
  }else{
      $_SESSION['error'] = 'Please scan your QR Code';
  }
  
  // Remove the redirect
  // header("location: member_qrlogin.php");
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Scanner</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
  <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    table {
      border: 1px solid #ddd;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
  </style>
</head>
<body>
<main class="container">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <video id="preview" width="100%"></video>
      </div>
      <div class="col-md-6">
        <form action="" method="post" class="form-horizontal" id="qr-form">
          <label>SCAN QR CODE</label>
          <input type="text" name="text" id="text" readonly="" placeholder="scan qrcode" class="form-control">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name"><br><br>
          <label for="student_id">Student ID:</label>
          <input type="text" id="student_id" name="student_id"><br><br>
          <label for="password">Password:</label>
          <input type="text" id="password" name="password"><br><br>
          <button type="submit" id="submit-btn" class="btn btn-primary">Continue</button>
        </form>
      </div>
    </div>
  </div>

  <script>
  let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

  Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
      scanner.start(cameras[0]);
      console.log('Camera started');
    } else {
      alert('No cameras found.');
    }
  }).catch(function (e) {
    console.error('Error accessing camera:', e);
  });

  scanner.addListener('scan', function (content) {
    console.log('QR code scanned:', content);
    document.getElementById('text').value = content;
  });

  scanner.addListener('active', function () {
    console.log('Scanner is active');
  });

  scanner.addListener('inactive', function () {
    console.log('Scanner is inactive');
  });

 document.getElementById('submit-btn').addEventListener('click', function() {
    let name = document.getElementById('name').value;
    let studentId = document.getElementById('student_id').value;
    let password = document.getElementById('password').value;

    if (name === '' || studentId === '' || password === '') {
      alert('QR CODE NOT REGISTERED');
      return;
    }

    // Submit the form
    document.getElementById('qr-form').submit();
});
</script>

<script>
    scanner.addListener('scan', function (content) {
        console.log('QR code scanned:', content);
        let qrData = content.split('\n');
        let name = qrData[0].split(':')[1].trim();
        let studentId = qrData[1].split(':')[1].trim();
        let password = qrData[2].split(':')[1].trim();

        // Fill the form fields with the extracted information
        document.getElementById('name').value = name;
        document.getElementById('student_id').value = studentId;
        document.getElementById('password').value = password;
    });
</script>

</main>

<!-- Display attendance records -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">

<main class="container">
<table id="example" class="table table-striped">
<thead>
    <tr>
      <th>Student ID</th>
      <th>Name</th>
      <th>Time In</th>
      <th>Time Out</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM attendance";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['student_id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['TIMEIN'] . "</td>";
      echo "<td>" . (isset($row['TIMEMOUT']) ? $row['TIMEMOUT'] : '') . "</td>";
      echo "</tr>";
    }
    ?>

    
  </tbody>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</table>

<?php
if(isset($_SESSION['success'])){
  echo "<p style='color:green'>{$_SESSION['success']}</p>";
  unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
  echo "<p style='color:red'>{$_SESSION['error']}</p>";
  unset($_SESSION['error']);
}


// Close the database connection
$conn->close();
?>