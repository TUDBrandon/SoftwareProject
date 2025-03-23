<?php
require_once '../includes/functions.php';
require_once '../includes/handle_submit.php';
init_session();

$success_message = '';
$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_submit_form($_POST, $_FILES);
    
    if (empty($errors)) {
        try {
            $item_id = save_submit($_POST, $_FILES);
            $success_message = "Item submitted successfully! Your item ID is: " . $item_id;
            
            // Clear form data on success
            $_POST = [];
        } catch (Exception $e) {
            $error_messages[] = "An error occurred while saving your submission. Please try again.";
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
    <title>Submit Item - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('sell'); ?>
    
    <main>
        <section class="submit-form">
            <h2>Submit Your Item</h2>
            
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
                echo create_form_field('email', 'Email Address', 'email', true, $_POST['email'] ?? '');
                
                // Item Information
                echo create_form_field('title', 'Item name', 'text', true, $_POST['title'] ?? '');
                
                // Category Selection
                ?>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="">Select a category</option>
                        <option value="gaming" <?php echo (isset($_POST['category']) && $_POST['category'] === 'gaming') ? 'selected' : ''; ?>>Gaming</option>
                        <option value="hardware" <?php echo (isset($_POST['category']) && $_POST['category'] === 'hardware') ? 'selected' : ''; ?>>Hardware</option>
                        <option value="phones" <?php echo (isset($_POST['category']) && $_POST['category'] === 'phones') ? 'selected' : ''; ?>>Phones</option>
                        <option value="laptops" <?php echo (isset($_POST['category']) && $_POST['category'] === 'laptops') ? 'selected' : ''; ?>>Laptops</option>
                        <option value="accessories" <?php echo (isset($_POST['category']) && $_POST['category'] === 'accessories') ? 'selected' : ''; ?>>Accessories</option>
                    </select>
                </div>
                <?php
                echo create_form_field('description', 'Condition of item', 'textarea', true, $_POST['description'] ?? '');
                echo create_form_field('item_image', 'Images of Item (JPG or PNG only, max 5MB)', 'file', true);
                ?>
                <button type="submit">Submit for Review</button>
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