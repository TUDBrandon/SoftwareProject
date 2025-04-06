<?php
require_once 'src/DBconnect.php';

echo "<h2>Database Column Check</h2>";

try {
    global $connection;
    
    // Check submission table
    $stmt = $connection->prepare("DESCRIBE submission");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Submission Table Columns:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li><strong>" . $column['Field'] . "</strong> - Type: " . $column['Type'] . "</li>";
    }
    echo "</ul>";
    
    // Check report table
    $stmt = $connection->prepare("DESCRIBE report");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Report Table Columns:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li><strong>" . $column['Field'] . "</strong> - Type: " . $column['Type'] . "</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
