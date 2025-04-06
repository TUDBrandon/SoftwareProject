<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Intel Core i7 13th Gen';
$price = '399.99';
$description = 'The Intel Core i7 13th Generation processor delivers exceptional performance for gaming, content creation, and multitasking. With its hybrid architecture combining performance and efficiency cores, advanced overclocking capabilities, and support for the latest technologies, this CPU provides the power you need for demanding applications.';
$image_path = 'images/CPUi713th.jpg';
$image_alt = 'Intel Core i7 13th Generation Processor';

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
