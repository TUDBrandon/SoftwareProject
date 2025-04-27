<?php
require_once '../includes/common.php';
$category = isset($_GET['category']) ? $_GET['category'] : '';
require_once '../includes/functions.php';
require_once '../includes/classes/Product.php';
require_once '../includes/classes/ProductRepository.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establish database connection
try {
    require_once __DIR__ . '/../src/DBconnect.php';
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}

// Create product repository
$repository = new ProductRepository($connection);

// Get products by category using the repository
$hardwareProducts = $repository->getHardwareProducts();
$consoleProducts = $repository->getConsoleProducts();
$phoneProducts = $repository->getPhoneProducts();
$gameProducts = $repository->getGameProducts();

// Convert Product objects to arrays for display
$hardware = $repository->toArrays($hardwareProducts);
$consoles = $repository->toArrays($consoleProducts);
$phones = $repository->toArrays($phoneProducts);
$games = $repository->toArrays($gameProducts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('buy'); ?>

    <main>
        <section class="hero">
            <h2>Browse Our Products</h2>
            <p>Find the latest tech, games, and more</p>
        </section>

        <section id="hardware" class="category-section">
            <h2>Hardware</h2>
            <?php echo display_product_carousel($hardware, 'hardware'); ?>
        </section>

        <section id="consoles" class="category-section">
            <h2>Consoles</h2>
            <?php echo display_product_carousel($consoles, 'consoles'); ?>
        </section>

        <section id="phones" class="category-section">
            <h2>Phones</h2>
            <?php echo display_product_carousel($phones, 'phones'); ?>
        </section>

        <section id="games" class="category-section">
            <h2>Games</h2>
            <?php echo display_product_carousel($games, 'games'); ?>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> TechTrade. All rights reserved.</p>
        </div>
    </footer>

    <?php echo include_carousel_js(); ?>
</body>
</html>