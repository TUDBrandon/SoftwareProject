<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Tekken 8 - PS5';
$price = '69.99';
$description = 'Tekken 8 for PlayStation 5 brings the legendary fighting franchise to new heights with stunning visuals powered by Unreal Engine 5. Experience the next chapter in the Mishima saga with an expanded roster of fighters, revolutionary combat mechanics, and a cinematic story mode. With its detailed character models, fluid animations, and responsive controls, this is the ultimate Tekken experience for both veterans and newcomers.';
$image_path = 'images/tekken8PS5.jpg';
$image_alt = 'Tekken 8 for PlayStation 5';

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
