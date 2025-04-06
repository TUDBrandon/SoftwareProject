<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Samsung Galaxy S23 Ultra';
$price = '1,199';
$description = 'The Samsung Galaxy S23 Ultra is the ultimate Android flagship smartphone. Featuring a stunning 6.8-inch Dynamic AMOLED 2X display, powerful Snapdragon processor, revolutionary 200MP camera system with advanced night photography capabilities, and the integrated S Pen for enhanced productivity. Experience exceptional performance, all-day battery life, and Samsung\'s premium ecosystem in this sophisticated device.';
$image_path = 'images/samsungGalazyS23Ultra.jpg';
$image_alt = 'Samsung Galaxy S23 Ultra Smartphone';

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
