<?php
/**
 * Database initialization script
 * This script creates all necessary tables for the TechTrade application
 */

require_once __DIR__ . "/../config.php";

try {
    $connection = new PDO($dsn, $username, $password, $options);
    
    // SQL to create tables
    $sql = file_get_contents(__DIR__ . "/init.sql");
    
    // Execute the SQL
    $connection->exec($sql);
    
    echo "Database tables created successfully!";
    
} catch(PDOException $error) {
    echo "Error creating database tables: " . $error->getMessage();
}
?>
