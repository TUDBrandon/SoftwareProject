<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'Dogman - Nintendo Switch';
$price = '39.99';
$description = 'Dogman for Nintendo Switch brings the beloved graphic novel series to life in an exciting adventure game. Play as the half-dog, half-human hero as you solve puzzles, fight crime, and save the day. Perfect for fans of the books and newcomers alike, this family-friendly game offers hours of entertaining gameplay on the go or at home.';
$image_path = 'images/dogmanSwitch.jpg';
$image_alt = 'Dogman Game for Nintendo Switch';

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
