<?php
session_start();
$category = isset($_GET['category']) ? $_GET['category'] : '';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products - TechTrade</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="main-nav">
            <div class="nav-left">
                <a href="index.php" class="logo">TechTrade</a>
                <ul class="nav-links">
                    <li>
                        <a href="browse.php">Buy</a>
                        <ul class="dropdown">
                            <li><a href="browse.php?category=hardware#hardware">Hardware</a></li>
                            <li><a href="browse.php?category=consoles#consoles">Consoles</a></li>
                            <li><a href="browse.php?category=phones#phones">Phones</a></li>
                            <li><a href="browse.php?category=games#games">Games</a></li>
                        </ul>
                    </li>
                    <li><a href="submitForm.php">Sell</a></li>
                    <li><a href="gaming.php">Gaming</a></li>
                    <li><a href="hardware.php">Hardware</a></li>
                    <li><a href="account.php">Account</a></li>
                    <li><a href="contactUs.php">Contact Us</a></li>
                </ul>
            </div>
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="search" name="q" placeholder="Search for games, phones, tech...">
                    <button type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>Browse Our Products</h2>
            <p>Find the latest tech, games, and more</p>
        </section>

        <section id="hardware" class="category-section">
            <h2>Hardware</h2>
            <?php echo display_product_carousel(get_hardware_products(), 'hardware'); ?>
        </section>

        <section id="consoles" class="category-section">
            <h2>Consoles</h2>
            <?php echo display_product_carousel(get_console_products(), 'consoles'); ?>
        </section>

        <section id="phones" class="category-section">
            <h2>Phones</h2>
            <?php echo display_product_carousel(get_phone_products(), 'phones'); ?>
        </section>

        <section id="games" class="category-section">
            <h2>Games</h2>
            <?php echo display_product_carousel(get_game_products(), 'games'); ?>
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