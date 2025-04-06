<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Marvel\'s Spider-Man 2 - PS5';
$price = '69.99';
$description = 'Marvel\'s Spider-Man 2 for PlayStation 5 is the highly anticipated sequel that brings both Peter Parker and Miles Morales together in an epic adventure. Experience a stunning open-world New York City with unprecedented detail, lightning-fast loading, and immersive DualSense controller features. Face iconic villains including Venom in this PlayStation exclusive that pushes the boundaries of superhero gaming.';
$image_path = 'images/spiderman2PS5.png';
$image_alt = 'Marvel\'s Spider-Man 2 for PlayStation 5';

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
