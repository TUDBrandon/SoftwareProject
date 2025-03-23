<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
init_session();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? 'user';
$user_data = [];
$submissions = [];
$reports = [];

try {
    // Get user data based on user type
    switch ($user_type) {
        case 'admin':
            $stmt = $connection->prepare("SELECT * FROM admins WHERE admin_id = :id");
            break;
        case 'employee':
            $stmt = $connection->prepare("SELECT * FROM employees WHERE employee_id = :id");
            break;
        default:
            $stmt = $connection->prepare("SELECT * FROM users WHERE user_id = :id");
    }
    
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user_data = $stmt->fetch();
    
    // Get user submissions if user is a regular user
    if ($user_type === 'user') {
        $stmt = $connection->prepare("SELECT * FROM submission WHERE username = :username ORDER BY timestamps DESC");
        $stmt->bindParam(':username', $user_data['username']);
        $stmt->execute();
        $submissions = $stmt->fetchAll();
        
        // Get user reports
        $stmt = $connection->prepare("SELECT * FROM report WHERE username = :username ORDER BY timestamps DESC");
        $stmt->bindParam(':username', $user_data['username']);
        $stmt->execute();
        $reports = $stmt->fetchAll();
    }
    
    // Get reports assigned to employee if user is an employee
    if ($user_type === 'employee') {
        $stmt = $connection->prepare("SELECT * FROM report WHERE employee_id = :employee_id ORDER BY timestamps DESC");
        $stmt->bindParam(':employee_id', $user_id);
        $stmt->execute();
        $reports = $stmt->fetchAll();
    }
    
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('account'); ?>
    
    <main class="container">
        <section class="account-section">
            <h1>My Account</h1>
            
            <div class="account-header">
                <h2>Welcome, <?= escape($user_data['username'] ?? $user_data['first_name'] ?? 'User') ?>!</h2>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
            
            <div class="account-details">
                <h3>Account Information</h3>
                <div class="account-info">
                    <?php if ($user_type === 'user'): ?>
                        <p><strong>Username:</strong> <?= escape($user_data['username']) ?></p>
                        <p><strong>Email:</strong> <?= escape($user_data['email']) ?></p>
                        <p><strong>Name:</strong> <?= escape($user_data['first_name']) ?></p>
                        <?php if (!empty($user_data['age'])): ?>
                            <p><strong>Age:</strong> <?= escape($user_data['age']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($user_data['phone_number'])): ?>
                            <p><strong>Phone:</strong> <?= escape($user_data['phone_number']) ?></p>
                        <?php endif; ?>
                    <?php elseif ($user_type === 'employee'): ?>
                        <p><strong>Name:</strong> <?= escape($user_data['first_name'] . ' ' . $user_data['last_name']) ?></p>
                        <p><strong>Email:</strong> <?= escape($user_data['email']) ?></p>
                        <p><strong>Employee ID:</strong> <?= escape($user_data['employee_id']) ?></p>
                        <?php if (!empty($user_data['phone_number'])): ?>
                            <p><strong>Phone:</strong> <?= escape($user_data['phone_number']) ?></p>
                        <?php endif; ?>
                    <?php elseif ($user_type === 'admin'): ?>
                        <p><strong>Username:</strong> <?= escape($user_data['username']) ?></p>
                        <p><strong>Email:</strong> <?= escape($user_data['email']) ?></p>
                        <p><strong>Admin ID:</strong> <?= escape($user_data['admin_id']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($user_type === 'user' && !empty($submissions)): ?>
                <div class="account-submissions">
                    <h3>My Submissions</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td><?= escape($submission['submission_id']) ?></td>
                                    <td><?= escape($submission['title']) ?></td>
                                    <td><?= escape($submission['category']) ?></td>
                                    <td><?= escape(date('Y-m-d', strtotime($submission['timestamps']))) ?></td>
                                    <td><?= escape($submission['status_update']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($reports)): ?>
                <div class="account-reports">
                    <h3><?= $user_type === 'employee' ? 'Assigned Reports' : 'My Reports' ?></h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <?php if ($user_type === 'employee'): ?>
                                    <th>Submitted By</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= escape($report['report_id']) ?></td>
                                    <td><?= escape($report['title']) ?></td>
                                    <td><?= escape(date('Y-m-d', strtotime($report['timestamps']))) ?></td>
                                    <td><?= escape($report['status_update']) ?></td>
                                    <?php if ($user_type === 'employee'): ?>
                                        <td><?= escape($report['username']) ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if ($user_type === 'admin'): ?>
                <div class="admin-actions">
                    <h3>Administrative Actions</h3>
                    <ul>
                        <li><a href="install.php" class="btn">Database Installation</a></li>
                        <li><a href="admin/users.php" class="btn">Manage Users</a></li>
                        <li><a href="admin/products.php" class="btn">Manage Products</a></li>
                        <li><a href="admin/reports.php" class="btn">View All Reports</a></li>
                        <li><a href="admin/submissions.php" class="btn">View All Submissions</a></li>
                    </ul>
                </div>
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
