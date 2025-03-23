<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Meta Quest 2 VR Headset';
$price = '299.99';
$description = 'The Meta Quest 2 (formerly Oculus Quest 2) is a revolutionary all-in-one VR headset that delivers immersive virtual reality without the need for a PC or console. Featuring high-resolution displays, intuitive controllers, and access to a vast library of games and experiences, the Quest 2 makes VR more accessible than ever. Step into new worlds and experience gaming like never before with this powerful standalone VR system.';
$image_path = 'images/oculus2.jpg';
$image_alt = 'Meta Quest 2 VR Headset';

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
