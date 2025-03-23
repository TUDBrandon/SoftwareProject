<?php
require_once '../includes/common.php';
require_once '../includes/classes/Product.php';
require_once '../includes/classes/ProductRepository.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establish database connection
try {
    require_once __DIR__ . '/../src/DBconnect.php';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Create a product repository
$repository = new ProductRepository($connection);

// Get products by category
$hardwareProducts = $repository->getHardwareProducts();
$consoleProducts = $repository->getConsoleProducts();
$phoneProducts = $repository->getPhoneProducts();
$gameProducts = $repository->getGameProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Class Demo</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .demo-section {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .product-card {
            border: 1px solid #eee;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            width: 200px;
            vertical-align: top;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Product Class Demo</h1>
        <p>Demonstrating encapsulation and consistent image handling</p>
    </header>
    
    <main>
        <section class="demo-section">
            <h2>Encapsulation Example</h2>
            <p>The Product class encapsulates data and provides controlled access through getters and setters:</p>
            <pre>
// Create a product with encapsulated data
$product = new Product([
    'name' => 'Example Product',
    'price' => 99.99,
    'image' => 'public/images/example.jpg'
]);

// Access data through getters
echo $product->getName();  // "Example Product"
echo $product->getPrice(); // 99.99

// Image path is automatically fixed
echo $product->getImage(); // "images/example.jpg" (public/ prefix removed)
            </pre>
        </section>
        
        <section class="demo-section">
            <h2>Hardware Products (<?php echo count($hardwareProducts); ?>)</h2>
            <?php foreach ($hardwareProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>">
                <h3><?php echo $product->getName(); ?></h3>
                <p>Price: <?php echo $product->getFormattedPrice(); ?></p>
                <p>Category: <?php echo $product->getCategory(); ?></p>
            </div>
            <?php endforeach; ?>
        </section>
        
        <section class="demo-section">
            <h2>Console Products (<?php echo count($consoleProducts); ?>)</h2>
            <?php foreach ($consoleProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>">
                <h3><?php echo $product->getName(); ?></h3>
                <p>Price: <?php echo $product->getFormattedPrice(); ?></p>
                <p>Category: <?php echo $product->getCategory(); ?></p>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
    
    <footer>
        <p>This demo shows how the Product class encapsulates data and handles image paths consistently.</p>
    </footer>
</body>
</html>
