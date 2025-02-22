<?php
require_once '../includes/functions.php';
require_once '../includes/handle_report.php';
init_session();

$success_message = '';
$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_report_form($_POST, $_FILES);
    
    if (empty($errors)) {
        try {
            $report_id = save_report($_POST, $_FILES);
            $success_message = "Report submitted successfully! Your report ID is: " . $report_id;
            
            // Clear form data on success
            $_POST = [];
        } catch (Exception $e) {
            $error_messages[] = "An error occurred while saving your report. Please try again.";
        }
    } else {
        $error_messages = $errors;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Secondhand Item - TechTrade</title>
    <link rel="stylesheet" href="css/style.css">
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
        <section class="submit-form">
            <h2>Report A Faulty Secondhand Item</h2>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_messages)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($error_messages as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <?php
                // Customer Information
                echo create_form_field('customer_name', 'Your Full Name', 'text', true, $_POST['customer_name'] ?? '');
                echo create_form_field('employee_code', 'Employee Code (found on receipt)', 'text', true, $_POST['employee_code'] ?? '');
                
                // Item Information
                echo create_form_field('title', 'Item name', 'text', true, $_POST['title'] ?? '');
                echo create_form_field('date', 'Date of Purchase', 'date', true, $_POST['date'] ?? '');
                echo create_form_field('expectation', 'What was disclosed at sale?', 'textarea', true, $_POST['expectation'] ?? '');
                echo create_form_field('description', 'Condition of item', 'textarea', true, $_POST['description'] ?? '');
                echo create_form_field('technical', 'Technical Problems', 'textarea', true, $_POST['technical'] ?? '');
                
                // File uploads
                echo create_form_field('item_image', 'Image of Item', 'file', true);
                echo create_form_field('receipt_image', 'Image of Receipt/Proof of Purchase', 'file', true);
                ?>
                <button type="submit">Submit Report</button>
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