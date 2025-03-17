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

  $sql = "SELECT * FROM employees;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Employee ID: " . $row["employee_id"] . " - First Name: " . $row["first_name"] .
           " - Last Name: " . $row["last_name"] . " - Email: " . $row["email"] .
           " - Phone Number: " . $row["phone_number"] . " - Updated By: " . $row["update_by"] . "<br>";
  }

  mysqli_close($conn);
?>