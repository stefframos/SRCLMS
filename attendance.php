<?php
include 'db.php';

if(isset($_POST['text'])){
    $text = $_POST['text'];
    $date = date('Y-m-d');
  
    $sql = "SELECT * FROM attendance WHERE student_id='$text' AND LOGDATE = '$date'";
    $query = $conn->query($sql);
    
    if($query ->num_rows > 0){
      $row = $query->fetch_assoc();
      if($row['STATUS'] == '0'){
        if(empty($row['TIMEMOUT'])){ // Check if TIMEMOUT is empty
          $sql = "UPDATE attendance SET TIMEMOUT=NOW(), STATUS='1' WHERE student_id='$text' AND LOGDATE= '$date'";
          $query = $conn->query($sql);
          $_SESSION['success'] = 'Successfuly time out';
        } else {
          // Do nothing if TIMEMOUT is already set
        }
      }else{
        // If the student has already timed out, do nothing
      }
    }else{
      $sql = "INSERT INTO attendance(student_id,TIMEIN,LOGDATE,STATUS) VALUES ('$text',NOW(),'$date','0')";
      if($conn->query($sql) === TRUE){
        $_SESSION['success'] = 'Successfuly time in';
      }else{
        $_SESSION['error'] = $conn->error ;
      }
    }
  
  }else{
      $_SESSION['error'] = 'Please scan your QR Code';
  }
  
  header("location: member_qrlogin.php");
  
  $conn->close();

?>