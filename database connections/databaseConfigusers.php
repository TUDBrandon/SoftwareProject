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

  $sql = "SELECT * FROM users;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "User ID: " . $row["user_id"] . " - Username: " . $row["username"] .
           " - Email: " . $row["email"] . " - First Name: " . $row["first_name"] .
           " - Age: " . $row["age"] . " - Phone Number: " . $row["phone_number"] . "<br>";
  }

  mysqli_close($conn);
?>
