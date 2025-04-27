<?php
session_start();
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Establish database connection
try {
    require_once __DIR__ . '/../src/DBconnect.php';
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrade - Buy, Sell, Exchange Tech</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php echo generate_navbar('buy'); ?>

    <main>
        <section class="hero">
            <h2>Trade in Your Tech for Cash or Store Credit</h2>
            <p>Buy, sell, or exchange games, phones, and other tech</p>
            <div class="cta-buttons">
                <a href="submitForm.php" class="sell-button">Sell Your Tech</a>
                <a href="reportForm.php" class="value-checker">Report an Issue</a>
            </div>
        </section>

        <section class="featured-categories">
            <div class="category">Gaming</div>
            <div class="category">Hardware</div>
            <div class="category">Consoles</div>
        </section>

        <section class="bestsellers">
            <h2>Top Selling Products</h2>
            <div class="product-grid">
                <?php
                $bestsellers = get_bestselling_products(5);

                foreach($bestsellers as $product) {
                    echo "<div class='product-card'>";
                    echo "<a href='{$product['link']}' class='product-link'>";
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p class='category'>{$product['category']}</p>";
                    echo "<p class='price'>€" . number_format($product['price'], 2) . "</p>";
                    echo "</a>";
                    echo "<button class='buy-now'>Buy Now</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> TechTrade. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
