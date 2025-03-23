<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrade - Buy, Sell, Exchange Tech</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <nav class="main-nav">
            <div class="nav-left">
                <a href="index.php" class="logo">TechTrade</a>
                <ul class="nav-links">
                    <li><a href="index.php">Buy</a></li>
                    <li><a href="submitForm.php">Sell</a></li>
                    <li><a href="gaming.php">Gaming</a></li>
                    <li><a href="hardware.php">Hardware</a></li>
                    <li><a href="account.php">Account</a></li>
                </ul>
            </div>
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="search" name="q" placeholder="Search for games, phones, tech...">
                    <button type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>Trade in Your Tech for Cash or Store Credit</h2>
            <p>Buy, sell, or exchange games, phones, and other tech</p>
            <div class="cta-buttons">
                <a href="submitForm.php" class="sell-button">Sell Your Tech</a>
                <a href="reportForm.php" class="value-checker">Report an Issue</a>
            </div>
        </section>

        <h1 class="text">Find us just here by looking at this map!</h1>

        <p><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d38106.55545999721!2d-6.333929300308229!3d53.34934730612041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48670e85c882be91%3A0xe8fa18631a4e94de!2sTECH%20FIX!5e0!3m2!1sen!2sie!4v1740331875686!5m2!1sen!2sie" width="1300" height="600" style="border:0;" allowfullscreen="" loading="lazy"  referrerpolicy="no-referrer-when-downgrade"></iframe></p>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> TechTrade. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
