<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
init_session();

$error_messages = [];
$success_message = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $age = $_POST['age'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate form data
    if (empty($username)) {
        $error_messages[] = "Username is required";
    }
    
    if (empty($email)) {
        $error_messages[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Please enter a valid email address";
    }
    
    if (empty($first_name)) {
        $error_messages[] = "First name is required";
    }
    
    if (empty($password)) {
        $error_messages[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $error_messages[] = "Password must be at least 8 characters long";
    }
    
    if ($password !== $confirm_password) {
        $error_messages[] = "Passwords do not match";
    }
    
    // Check if username or email already exists
    if (empty($error_messages)) {
        try {
            $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error_messages[] = "Username or email already exists";
            }
        } catch (PDOException $e) {
            $error_messages[] = "Error checking existing users: " . $e->getMessage();
        }
    }
    
    // Register user if no errors
    if (empty($error_messages)) {
        try {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $connection->prepare("
                INSERT INTO users (username, email, first_name, age, phone_number, password) 
                VALUES (:username, :email, :first_name, :age, :phone_number, :password)
            ");
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':password', $hashed_password);
            
            $stmt->execute();
            
            $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
            
            // Clear form data on success
            $_POST = [];
            
        } catch (PDOException $e) {
            $error_messages[] = "Registration error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('register'); ?>
    
    <main class="container">
        <section class="register-section">
            <h1>Create a TechTrade Account</h1>
            
            <?php if (!empty($error_messages)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($error_messages as $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?= $success_message ?>
                </div>
            <?php else: ?>
                <form method="post" class="register-form">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" value="<?= escape($_POST['username'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?= escape($_POST['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" value="<?= escape($_POST['first_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" id="age" value="<?= escape($_POST['age'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="tel" name="phone_number" id="phone_number" value="<?= escape($_POST['phone_number'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                        <small>Password must be at least 8 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                    
                    <p class="login-link">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </form>
            <?php endif; ?>
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
