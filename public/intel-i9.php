<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Intel Core i9 Processor';
$price = '549.99';
$description = 'The Intel Core i9 processor represents the pinnacle of desktop computing performance. With its high core count, advanced hyperthreading technology, and impressive clock speeds, this CPU delivers exceptional performance for the most demanding tasks including gaming, content creation, 3D rendering, and scientific computing. Experience unparalleled processing power with Intel\'s flagship processor.';
$image_path = 'images/inteli9.jpg';
$image_alt = 'Intel Core i9 Processor';

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
