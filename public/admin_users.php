<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
init_session();

// Check if user is logged in as admin
$is_admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

// If not admin, redirect to login
if (!$is_admin) {
    // For testing purposes, you can comment out this redirect
    // header("Location: login.php");
    // exit;
    $admin_warning = "Warning: You are not logged in as an admin. This page would normally be restricted.";
}

// Get all users from the database
try {
    $stmt = $connection->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    
    // Get all employees
    $stmt = $connection->query("SELECT * FROM employees");
    $employees = $stmt->fetchAll();
    
    // Get all admins
    $stmt = $connection->query("SELECT * FROM admins");
    $admins = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .admin-panel {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .user-table th, .user-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .user-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .user-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .user-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .admin-warning {
            background-color: #ffdddd;
            color: #990000;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php echo generate_navbar('admin'); ?>
    
    <main class="container">
        <section class="admin-panel">
            <h1>User Management</h1>
            
            <?php if (isset($admin_warning)): ?>
                <div class="admin-warning">
                    <?php echo $admin_warning; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php else: ?>
                <h2>Regular Users</h2>
                <?php if (empty($users)): ?>
                    <p>No users found.</p>
                <?php else: ?>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Age</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo escape($user['user_id']); ?></td>
                                    <td><?php echo escape($user['username']); ?></td>
                                    <td><?php echo escape($user['email']); ?></td>
                                    <td><?php echo escape($user['first_name']); ?></td>
                                    <td><?php echo escape($user['age']); ?></td>
                                    <td><?php echo escape($user['phone_number']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                
                <h2>Employees</h2>
                <?php if (empty($employees)): ?>
                    <p>No employees found.</p>
                <?php else: ?>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Employee Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?php echo escape($employee['employee_id']); ?></td>
                                    <td><?php echo escape($employee['first_name']); ?></td>
                                    <td><?php echo escape($employee['last_name']); ?></td>
                                    <td><?php echo escape($employee['email']); ?></td>
                                    <td><?php echo escape($employee['employee_code']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                
                <h2>Admins</h2>
                <?php if (empty($admins)): ?>
                    <p>No admins found.</p>
                <?php else: ?>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Admin Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><?php echo escape($admin['admin_id']); ?></td>
                                    <td><?php echo escape($admin['username']); ?></td>
                                    <td><?php echo escape($admin['email']); ?></td>
                                    <td><?php echo escape($admin['admin_level']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
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
