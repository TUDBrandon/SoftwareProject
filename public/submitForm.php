<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';
require_once '../includes/handle_submit.php';
require_once '../src/DBconnect.php';
init_session();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with a message
    $_SESSION['login_message'] = "You must be logged in to submit an item.";
    header("Location: login.php");
    exit;
}

// Check if user is an employee (not allowed to create submissions)
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employee') {
    // Redirect to account page with a message
    $_SESSION['error_message'] = "Employees cannot submit items. Please contact an administrator.";
    header("Location: account.php");
    exit;
}

$success_message = '';
$error_messages = [];

// Get user data for pre-filling the form
$user_data = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'] ?? 'user';
    
    try {
        // Get user data based on user type
        switch ($user_type) {
            case 'admin':
                $stmt = $connection->prepare("SELECT * FROM admins WHERE admin_id = :id");
                break;
            default:
                $stmt = $connection->prepare("SELECT * FROM users WHERE user_id = :id");
        }
        
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_messages[] = "Database error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_submit_form($_POST, $_FILES);
    
    if (empty($errors)) {
        try {
            // Add customer_id to the submission data
            $_POST['customer_id'] = $_SESSION['user_id'];
            
            $item_id = save_submit($_POST, $_FILES);
            $success_message = "Item submitted successfully!";
            
            // Clear form data on success
            $_POST = [];
        } catch (Exception $e) {
            // Display the actual error message
            $error_messages[] = "Error: " . $e->getMessage();
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
                // Customer Information - Pre-fill with user data if available
                $customer_name = $user_data['username'] ?? $user_data['first_name'] ?? ($_POST['customer_name'] ?? '');
                $email = $user_data['email'] ?? ($_POST['email'] ?? '');
                
                echo create_form_field('customer_name', 'Your Full Name', 'text', true, $customer_name);
                echo create_form_field('email', 'Email Address', 'email', true, $email);
                
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
                        <option value="consoles" <?php echo (isset($_POST['category']) && $_POST['category'] === 'consoles') ? 'selected' : ''; ?>>Consoles</option>
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