<?php
require_once '../includes/functions.php';
init_session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Item - TechTrade</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>TechTrade</h1>
        </div>
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="index.php">Buy</a></li>
                <li><a href="submitForm.php">Sell</a></li>
                <li><a href="gaming.php">Gaming</a></li>
                <li><a href="hardware.php">Hardware</a></li>
                <li><a href="account.php">Account</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="submit-form">
            <h2>Submit Your Item</h2>
            <form class="form"action="" method="POST">
                <?php
                echo create_form_field('title', 'Item name');
                echo create_form_field('description', 'Condition of item', 'textarea');
                ?>
                <button class="button-form" type="submit">Submit Item</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> TechTrade. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>