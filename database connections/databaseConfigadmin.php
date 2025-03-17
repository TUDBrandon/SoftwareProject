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

  $sql = "SELECT * FROM admin;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Admin ID: " . $row["admin_id"] . " - Updated By: " . $row["update_by"] .
           " - First Name: " . $row["first_name"] . " - Last Name: " . $row["last_name"] .
           " - Email: " . $row["email"] . "<br>";
  }

  mysqli_close($conn);
?>
