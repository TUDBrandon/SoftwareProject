<?php
/**
 * Database connection using PDO
 */

// Include database configuration
require_once __DIR__ . "/config.php";

try {
    $connection = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // For development - in production you would want to handle this differently
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
