<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'iPhone 14 - Black';
$price = '799';
$description = 'The iPhone 14 in elegant black combines style with cutting-edge technology. Featuring Apple\'s powerful A15 Bionic chip, an advanced camera system, Super Retina XDR display, and all-day battery life. Experience the perfect blend of performance and sophisticated design with this premium smartphone.';
$image_path = 'images/black_iphone14.jpg';
$image_alt = 'iPhone 14 in Black';

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
    <?php echo generate_navbar('buy'); ?>
    
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
