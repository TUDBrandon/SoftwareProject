<?php
require_once '../includes/common.php';
require_once '../includes/functions.php';
require_once '../includes/handle_report.php';
require_once '../src/DBconnect.php';
init_session();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with a message
    $_SESSION['login_message'] = "You must be logged in to submit a report.";
    header("Location: login.php");
    exit;
}

// Check if user is an employee (not allowed to create reports)
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employee') {
    // Redirect to account page with a message
    $_SESSION['error_message'] = "Employees cannot submit reports. Please contact an administrator.";
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
    $errors = validate_report_form($_POST, $_FILES);
    
    if (empty($errors)) {
        try {
            // Add customer_id to the report data
            $_POST['customer_id'] = $_SESSION['user_id'];
            
            $report_id = save_report($_POST, $_FILES);
            $success_message = "Report submitted successfully!";
            
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
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('report'); ?>
    
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
                // Customer Information - Pre-fill with user data if available
                $customer_name = $user_data['username'] ?? $user_data['first_name'] ?? ($_POST['customer_name'] ?? '');
                echo create_form_field('customer_name', 'Your Full Name', 'text', true, $customer_name);
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