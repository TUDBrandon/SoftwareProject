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

  $sql = "SELECT * FROM report;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Report ID: " . $row["report_id"] . " - Timestamp: " . $row["timestamp"] .
           " - Username: " . $row["username"] . " - Title: " . $row["title"] .
           " - Date: " . $row["date"] . " - Description: " . $row["description"] .
           " - Technical Worker: " . $row["technical_worker"] . " - Status Update: " . $row["status_update"] .
           " - Employee ID: " . $row["employee_id"] . " - Expectation: " . $row["expectation"] . "<br>";
  }

  mysqli_close($conn);
?>
