<?php
/**
 * Configuration for database connection
 */

$host       = "localhost";
$username   = "techtrade";
$password   = "techtrade";
$dbname     = "techtrade";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Check if the database exists, if not create it
try {
    $pdo = new PDO("mysql:host=$host", $username, $password, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>
