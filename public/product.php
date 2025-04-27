<?php
session_start();
require_once '../includes/common.php';
require_once '../includes/functions.php';
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
                <button class="sell-button">Sell Your Tech</button>
                <button class="value-checker">Check Trade-in Value</button>
            </div>
        </section>

        <section class="featured-categories">
            <!-- We can fill these in with actual categories later -->
            <div class="product">
                <h3>MSI RX570 8GBS</h3>
                <p>€120</p>
                <p>This Entry level graphics card released in April 18th, 2017 and has been preforminng just great since all those years</p>
                
                <img src="images/msi370.jpg" alt="MSI 370 Motherboard">
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 TechTrade. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
