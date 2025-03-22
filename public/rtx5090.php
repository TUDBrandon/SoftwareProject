<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'NVIDIA RTX 5090';
$price = '1,999';
$description = 'The NVIDIA GeForce RTX 5090 represents the absolute pinnacle of graphics processing power. This next-generation flagship GPU features unprecedented CUDA cores, ray tracing capabilities, and AI-enhanced performance. With its massive VRAM capacity and revolutionary architecture, the RTX 5090 delivers breathtaking visuals and framerates even in the most demanding 8K gaming scenarios and professional workloads.';
$image_path = 'images/rtx5090.jpg';
$image_alt = 'NVIDIA RTX 5090 Graphics Card';

// Output the page content directly
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <nav class="main-nav">
            <div class="nav-left">
                <a href="index.php" class="logo">TechTrade</a>
                <ul class="nav-links">
                    <li><a href="index.php">Buy</a></li>
                    <li><a href="submitForm.php">Sell</a></li>
                    <li><a href="gaming.php">Gaming</a></li>
                    <li><a href="hardware.php">Hardware</a></li>
                    <li><a href="account.php">Account</a></li>
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
        <section class="featured-categories">
            <div class="product">
                <h3><?php echo htmlspecialchars($title); ?></h3>
                <p>€<?php echo htmlspecialchars($price); ?></p>
                <p><?php echo htmlspecialchars($description); ?></p>
                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($image_alt); ?>">
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
