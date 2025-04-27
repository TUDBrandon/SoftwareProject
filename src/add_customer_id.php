<?php
require_once 'src/DBconnect.php';

echo "<h2>Add Customer ID Columns</h2>";

try {
    global $connection;
    
    // Check if customer_id column exists in submission table
    $stmt = $connection->prepare("SHOW COLUMNS FROM submission LIKE 'customer_id'");
    $stmt->execute();
    $column_exists = $stmt->rowCount() > 0;
    
    if (!$column_exists) {
        // Add customer_id column to submission table
        $connection->exec("ALTER TABLE submission ADD COLUMN customer_id INT, ADD FOREIGN KEY (customer_id) REFERENCES users(user_id)");
        echo "<p style='color:green;'>Added customer_id column to submission table.</p>";
    } else {
        echo "<p>customer_id column already exists in submission table.</p>";
    }
    
    // Check if customer_id column exists in report table
    $stmt = $connection->prepare("SHOW COLUMNS FROM report LIKE 'customer_id'");
    $stmt->execute();
    $column_exists = $stmt->rowCount() > 0;
    
    if (!$column_exists) {
        // Add customer_id column to report table
        $connection->exec("ALTER TABLE report ADD COLUMN customer_id INT, ADD FOREIGN KEY (customer_id) REFERENCES users(user_id)");
        echo "<p style='color:green;'>Added customer_id column to report table.</p>";
    } else {
        echo "<p>customer_id column already exists in report table.</p>";
    }
    
    echo "<p style='color:green;'>Operation completed successfully!</p>";
    echo "<p><a href='submitForm.php'>Go to Submit Form</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
