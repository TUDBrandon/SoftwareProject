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

  $sql = "SELECT * FROM products;";
  $qryResult = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($qryResult)) {
      echo "Product ID: " . $row["product_id"] . " - Product Name: " . $row["product_name"] .
           " - Price: " . $row["product_price"] . " - Description: " . $row["product_description"] .
           " - Updated By: " . $row["update_by"] . "<br>";
  }

  mysqli_close($conn);
?>
