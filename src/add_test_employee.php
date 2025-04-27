<?php
// Script to add a test employee account to the database
require_once __DIR__ . '/DBconnect.php';

try {
    // Check if test employee already exists
    $check_sql = "SELECT COUNT(*) FROM employees WHERE email = 'test.employee@techtrade.com'";
    $check_stmt = $connection->prepare($check_sql);
    $check_stmt->execute();
    
    if ($check_stmt->fetchColumn() > 0) {
        echo "Test employee account already exists.\n";
    } else {
        // Create a test employee account
        $sql = "INSERT INTO employees (first_name, last_name, email, phone_number, password) 
                VALUES (:first_name, :last_name, :email, :phone_number, :password)";
        
        $stmt = $connection->prepare($sql);
        
        $first_name = "Test";
        $last_name = "Employee";
        $email = "test.employee@techtrade.com";
        $phone_number = "123-456-7890";
        $password = password_hash("employee123", PASSWORD_DEFAULT); // Hashed password
        
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':password', $password);
        
        $stmt->execute();
        
        echo "Test employee account created successfully!\n";
        echo "Email: test.employee@techtrade.com\n";
        echo "Password: employee123\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
