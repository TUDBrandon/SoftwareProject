<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrade - Buy, Sell, Exchange Tech</title>
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
                $bestsellers = [
                    [
                        'name' => 'PlayStation 5',
                        'price' => 499.99,
                        'image' => 'images/ps5.jpg',
                        'category' => 'Gaming',
                        'link' => 'playstation5.php'
                    ],
                    [
                        'name' => 'MSI GTX 370',
                        'price' => 499.99,
                        'image' => 'images/msi370.jpg',
                        'category' => 'Hardware',
                        'link' => 'msi-rx570.php'
                    ],
                    [
                        'name' => 'CPU i7 13th Gen',
                        'price' => 349.99,
                        'image' => 'images/CPUi713th.jpg',
                        'category' => 'Hardware',
                        'link' => 'intel-i7.php'
                    ],
                    [
                        'name' => 'iPhone 14',
                        'price' => 499.99,
                        'image' => 'images/iphone14.jpg',
                        'category' => 'Hardware',
                        'link' => 'iphone14.php'
                    ],
                    [
                        'name' => 'COD Black Ops 6',
                        'price' => 59.99,
                        'image' => 'images/CodBO6.jpg',
                        'category' => 'Consoles',
                        'link' => 'cod-bo6.php'
                    ]
                ];

                for($i = 0; $i < count($bestsellers); $i++) {
                    $product = $bestsellers[$i];
                    echo "<div class='product-card'>";
                    echo "<a href='{$product['link']}' class='product-link'>";
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p class='category'>{$product['category']}</p>";
                    echo "<p class='price'>€{$product['price']}</p>";
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
