<?php
/**
 * Script to populate the products table with hardcoded product data
 */

// Include database connection
require_once __DIR__ . '/DBconnect.php';

// Check if connection is successful
if (!isset($connection) || !$connection) {
    die("Database connection failed. Please check your database settings.");
}

// Create products table if it doesn't exist
try {
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255) NOT NULL,
        category VARCHAR(50) NOT NULL,
        link VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $connection->exec($sql);
    echo "Products table created or already exists.<br>";
} catch (PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}

// Get all product data from our functions
require_once __DIR__ . '/../includes/functions.php';

// Collect all products from the hardcoded arrays
$allProducts = [
    // Hardware products
    [
        'name' => 'Graphics Card RTX 4090',
        'price' => 1599.99,
        'image' => 'public/images/rtx4090.jpg',
        'category' => 'Hardware',
        'link' => 'product.php?id=1'
    ],
    [
        'name' => 'MSI 370',
        'price' => 129.99,
        'image' => 'public/images/msi370.jpg',
        'category' => 'Hardware',
        'link' => 'product.php?id=2'
    ],
    [
        'name' => 'Intel i9',
        'price' => 299.99,
        'image' => 'public/images/inteli9.jpg',
        'category' => 'Hardware',
        'link' => 'product.php?id=3'
    ],
    [
        'name' => 'Intel i7',
        'price' => 229.99,
        'image' => 'public/images/CPUi713th.jpg',
        'category' => 'Hardware',
        'link' => 'product.php?id=4'
    ],
    [
        'name' => 'RTX 5090',
        'price' => 1999.99,
        'image' => 'public/images/rtx5090.jpg',
        'category' => 'Hardware',
        'link' => 'product.php?id=5'
    ],
    
    // Console products
    [
        'name' => 'PlayStation 5',
        'price' => 499.99,
        'image' => 'public/images/ps5.jpg',
        'category' => 'Consoles',
        'link' => 'product.php?id=6'
    ],
    [
        'name' => 'Xbox Series X',
        'price' => 499.99,
        'image' => 'public/images/xboxX.png',
        'category' => 'Consoles',
        'link' => 'product.php?id=7'
    ],
    [
        'name' => 'Nintendo Switch',
        'price' => 299.99,
        'image' => 'public/images/switch.jpg',
        'category' => 'Consoles',
        'link' => 'product.php?id=8'
    ],
    [
        'name' => 'Oculus 2',
        'price' => 399.99,
        'image' => 'public/images/oculus2.jpg',
        'category' => 'Consoles',
        'link' => 'product.php?id=9'
    ],
    [
        'name' => 'PlayStation 4 Pro',
        'price' => 299.99,
        'image' => 'public/images/ps4.jpg',
        'category' => 'Consoles',
        'link' => 'product.php?id=10'
    ],
    
    // Phone products
    [
        'name' => 'iPhone 15 Pro',
        'price' => 999.99,
        'image' => 'public/images/iphone15proMax.jpg',
        'category' => 'Phones',
        'link' => 'product.php?id=11'
    ],
    [
        'name' => 'Samsung Galaxy S23 Ultra',
        'price' => 899.99,
        'image' => 'public/images/samsungGalazyS23Ultra.jpg',
        'category' => 'Phones',
        'link' => 'product.php?id=12'
    ],
    [
        'name' => 'iPhone 15',
        'price' => 799.99,
        'image' => 'public/images/green_iphone15.jpg',
        'category' => 'Phones',
        'link' => 'product.php?id=13'
    ],
    [
        'name' => 'iPhone 14',
        'price' => 699.99,
        'image' => 'public/images/iphone14.jpg',
        'category' => 'Phones',
        'link' => 'product.php?id=14'
    ],
    [
        'name' => 'iPhone 14 Black',
        'price' => 599.99,
        'image' => 'public/images/black_iphone14.jpg',
        'category' => 'Phones',
        'link' => 'product.php?id=15'
    ],
    
    // Game products
    [
        'name' => 'Spiderman 2',
        'price' => 59.99,
        'image' => 'public/images/spiderman2PS5.png',
        'category' => 'Games',
        'link' => 'product.php?id=16'
    ],
    [
        'name' => 'Tekken 8',
        'price' => 49.99,
        'image' => 'public/images/tekken8PS5.jpg',
        'category' => 'Games',
        'link' => 'product.php?id=17'
    ],
    [
        'name' => 'Dogman',
        'price' => 59.99,
        'image' => 'public/images/dogmanSwitch.jpg',
        'category' => 'Games',
        'link' => 'product.php?id=18'
    ],
    [
        'name' => '2K25',
        'price' => 59.99,
        'image' => 'public/images/2k25PS5.jpg',
        'category' => 'Games',
        'link' => 'product.php?id=19'
    ],
    [
        'name' => 'Call of Duty: BO6',
        'price' => 39.99,
        'image' => 'public/images/CodBO6.jpg',
        'category' => 'Games',
        'link' => 'product.php?id=20'
    ],
    // Adding missing games
    [
        'name' => 'Grand Theft Auto V - Xbox',
        'price' => 29.99,
        'image' => 'public/images/GTAVXbox.jpg',
        'category' => 'Games',
        'link' => 'GTAVXbox.php'
    ],
    [
        'name' => 'Hogwarts Legacy - Xbox',
        'price' => 59.99,
        'image' => 'public/images/hogwartslegacyXbox.jpg',
        'category' => 'Games',
        'link' => 'hogwarts-legacy-xbox.php'
    ],
    [
        'name' => 'It Takes Two - Nintendo Switch',
        'price' => 39.99,
        'image' => 'public/images/ittakes2Switch.jpg',
        'category' => 'Games',
        'link' => 'it-takes-two-switch.php'
    ],
    [
        'name' => 'Instant Sports - Nintendo Switch',
        'price' => 29.99,
        'image' => 'public/images/instantsportsSwitch.jpg',
        'category' => 'Games',
        'link' => 'instant-sports-switch.php'
    ],
    [
        'name' => 'Steam Gift Card',
        'price' => 50.00,
        'image' => 'public/images/gitcardSteam.jpeg',
        'category' => 'Games',
        'link' => 'steam-giftcard.php'
    ]
];

// Clear existing products
try {
    $sql = "DELETE FROM products";
    $connection->exec($sql);
    echo "Cleared existing products.<br>";
} catch (PDOException $e) {
    echo "Warning: Could not clear existing products: " . $e->getMessage() . "<br>";
}

// Insert all products
$insertCount = 0;
$errorCount = 0;

foreach ($allProducts as $product) {
    try {
        $sql = "INSERT INTO products (name, price, image, category, link) 
                VALUES (:name, :price, :image, :category, :link)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $product['name']);
        $stmt->bindParam(':price', $product['price']);
        $stmt->bindParam(':image', $product['image']);
        $stmt->bindParam(':category', $product['category']);
        $stmt->bindParam(':link', $product['link']);
        $stmt->execute();
        $insertCount++;
    } catch (PDOException $e) {
        echo "Error inserting product '{$product['name']}': " . $e->getMessage() . "<br>";
        $errorCount++;
    }
}

echo "<strong>Database population complete!</strong><br>";
echo "Successfully inserted $insertCount products.<br>";
if ($errorCount > 0) {
    echo "Failed to insert $errorCount products.<br>";
}

// Check if products were actually inserted
try {
    $stmt = $connection->query("SELECT COUNT(*) as count FROM products");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total products in database: " . $count['count'] . "<br>";
    
    // Check categories
    $categories = ['Hardware', 'Consoles', 'Phones', 'Games'];
    foreach ($categories as $category) {
        $stmt = $connection->prepare("SELECT COUNT(*) as count FROM products WHERE category = ?");
        $stmt->execute([$category]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "$category products: " . $count['count'] . "<br>";
    }
} catch (Exception $e) {
    echo "Error checking products: " . $e->getMessage() . "<br>";
}

echo "<a href='../public/browse.php'>Go to Browse Page</a>";
?>
