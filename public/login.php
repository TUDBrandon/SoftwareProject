<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
init_session();

$error_message = '';
$success_message = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'user';
    
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required";
    } else {
        try {
            // Determine which table to check based on user type
            $table = '';
            $id_field = '';
            
            switch ($user_type) {
                case 'user':
                    $table = 'users';
                    $id_field = 'user_id';
                    break;
                case 'employee':
                    $table = 'employees';
                    $id_field = 'employee_id';
                    break;
                case 'admin':
                    $table = 'admins';
                    $id_field = 'admin_id';
                    break;
                default:
                    $table = 'users';
                    $id_field = 'user_id';
            }
            
            // Query for the user
            $sql = "SELECT * FROM $table WHERE username = :username";
            if ($table === 'employees') {
                $sql = "SELECT *, CONCAT(first_name, ' ', last_name) AS username FROM $table WHERE email = :username";
            }
            
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user[$id_field];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user_type;
                
                // Redirect based on user type
                switch ($user_type) {
                    case 'admin':
                        header("Location: admin/dashboard.php");
                        break;
                    case 'employee':
                        header("Location: employee/dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit;
            } else {
                $error_message = "Invalid username or password";
            }
        } catch (PDOException $e) {
            $error_message = "Login error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('login'); ?>
    
    <main class="container">
        <section class="login-section">
            <h1>Login to TechTrade</h1>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <?= escape($error_message) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?= escape($success_message) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="user_type">Login as:</label>
                    <select name="user_type" id="user_type">
                        <option value="user">User</option>
                        <option value="employee">Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="username">Username/Email:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
                <p class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </form>
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
