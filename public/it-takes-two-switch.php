<?php
require_once '../includes/functions.php';

// Use our create_product_page function to generate the content
$title = 'It Takes Two - Nintendo Switch';
$price = '39.99';
$description = 'It Takes Two for Nintendo Switch is an innovative cooperative adventure that takes two players on a heartfelt journey. Play as the clashing couple Cody and May, working together to navigate wildly varied gameplay challenges. With its unique split-screen only gameplay, creative level design, and emotional storytelling, this Game of the Year winner delivers an unforgettable experience that must be played together.';
$image_path = 'images/ittakes2Switch.jpg';
$image_alt = 'It Takes Two for Nintendo Switch';

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
