<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Hogwarts Legacy - Xbox';
$price = '59.99';
$description = 'Hogwarts Legacy for Xbox is an immersive, open-world action RPG set in the wizarding world of the 1800s. Experience life as a student at Hogwarts School of Witchcraft and Wizardry like never before. Explore familiar and new locations, discover magical beasts, craft potions, master spell casting, and become the wizard you want to be in this stunning adventure.';
$image_path = 'images/hogwartslegacyXbox.jpg';
$image_alt = 'Hogwarts Legacy for Xbox';

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
