<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'PlayStation 4';
$price = '299.99';
$description = 'The PlayStation 4 is Sony\'s iconic gaming console that revolutionized the gaming industry. With its powerful hardware, extensive game library, and innovative features, the PS4 delivers immersive gaming experiences for players of all ages. Enjoy exclusive titles, online multiplayer, streaming capabilities, and more with this versatile entertainment system.';
$image_path = 'images/ps4.jpg';
$image_alt = 'PlayStation 4 Console';

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
