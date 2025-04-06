<?php
/**
 * Database Installation Script
 * 
 * This script creates the database tables needed for the TechTrade application.
 * Run this script once to set up your database structure.
 */

require_once '../includes/functions.php';
require_once '../includes/common.php';
require_once './init_db.php';
init_session();

// Check if user is logged in as admin
$is_admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
$install_complete = false;
$error_message = '';

// Only allow installation if user is admin or if installation is forced
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['force_install']) || $is_admin)) {
    try {
        // Include the database initialization script
        require_once './init_db.php';
        $install_complete = true;
    } catch (Exception $e) {
        $error_message = "Installation failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Installation - TechTrade</title>
    <link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .install-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        code {
            display: block;
            background-color: #f1f1f1;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <?php echo generate_navbar('admin'); ?>
    
    <main class="install-container">
        <h1>TechTrade Database Installation</h1>
        
        <?php if ($install_complete): ?>
            <div class="success">
                <h2>Installation Complete!</h2>
                <p>The database tables have been created successfully.</p>
                <p><a href="../public/index.php" class="btn">Go to Homepage</a></p>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="error">
                <h2>Installation Error</h2>
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php else: ?>
            <?php if (!$is_admin): ?>
                <div class="warning">
                    <h2>Warning: Admin Access Required</h2>
                    <p>Normally, this installation should only be performed by an administrator.</p>
                    <p>If you are setting up the application for the first time, you can force the installation.</p>
                </div>
            <?php endif; ?>
            
            <p>This script will create the following database tables:</p>
            <ul>
                <li>users - For storing user account information</li>
                <li>employees - For storing employee account information</li>
                <li>admins - For storing admin account information</li>
                <li>products - For storing product information</li>
                <li>submission - For storing user submissions</li>
                <li>report - For storing user reports</li>
                <li>transactions - For storing transaction information</li>
            </ul>
            
            <p>The script will also populate the tables with sample data.</p>
            
            <form method="post" class="install-form">
                <?php if (!$is_admin): ?>
                    <div class="form-group">
                        <label for="force_install">
                            <input type="checkbox" name="force_install" id="force_install" value="1">
                            I understand the risks and want to force the installation
                        </label>
                    </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Install Database</button>
                    <a href="../public/index.php" class="btn">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About TechTrade</h3>
                <p>TechTrade is your one-stop shop for buying and selling tech products.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="../public/about.php">About Us</a></li>
                    <li><a href="../public/contact.php">Contact</a></li>
                    <li><a href="../public/terms.php">Terms of Service</a></li>
                    <li><a href="../public/privacy.php">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: info@techtrade.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 TechTrade. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
