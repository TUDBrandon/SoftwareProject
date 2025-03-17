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

  $sql = "SELECT * FROM transactions;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Transaction ID: " . $row["transaction_id"] . " - Product ID: " . $row["product_id"] .
           " - Card Number: " . $row["card_number"] . " - User ID: " . $row["user_id"] .
           " - Updated By: " . $row["update_by"] . "<br>";
  }

  mysqli_close($conn);
?>
