<?php
  $servername = "localhost"; 
  $username = "root"; 
  $password = "";
  $dbname = "techtrade"; 

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  echo "Successfully Connected to Database<br/><br/>";

  $sql = "SELECT * FROM submission;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Submission ID: " . $row["submission_id"] . " - Timestamp: " . $row["timestamp"] .
           " - Username: " . $row["username"] . " - Email: " . $row["email"] . " - Title: " . $row["title"] .
           " - Description: " . $row["description"] . " - Status Update: " . $row["status_update"] .
           " - Employee ID: " . $row["employee_id"] . "<br>";
  }

  mysqli_close($conn);
?>
