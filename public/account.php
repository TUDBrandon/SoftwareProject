<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
require_once '../includes/classes/User.php';
require_once '../includes/classes/Customer.php';
require_once '../includes/classes/Submission.php';
require_once '../includes/classes/Report.php';
require_once '../includes/classes/SubmissionRepository.php';
require_once '../includes/classes/ReportRepository.php';
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
    
    // Get user submissions and reports if user is a regular user
    if ($user_type === 'user') {
        // Create Customer object
        $customer = new Customer([
            'id' => $user_id,
            'username' => $user_data['username'],
            'email' => $user_data['email'],
            'first_name' => $user_data['first_name']
        ]);
        
        // Get customer's submissions and reports using the new methods
        $submissions = $customer->getSubmissions();
        $reports = $customer->getReports();
    }
    
    // Get reports assigned to employee if user is an employee
    if ($user_type === 'employee') {
        $reportRepository = new ReportRepository($connection);
        $reports = $reportRepository->getReportsByEmployeeId($user_id);
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
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td><?= escape($submission->getId()) ?></td>
                                    <td><?= escape($submission->getTitle()) ?></td>
                                    <td><?= escape($submission->getCategory()) ?></td>
                                    <td><?= escape($submission->getStatus()) ?></td>
                                    <td>
                                        <a href="view_submission.php?id=<?= $submission->getId() ?>" class="btn btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p><a href="submitForm.php" class="btn">Submit New Item</a></p>
                </div>
            <?php elseif ($user_type === 'user'): ?>
                <div class="account-submissions">
                    <h3>My Submissions</h3>
                    <p>You haven't submitted any items yet.</p>
                    <p><a href="submitForm.php" class="btn">Submit an Item</a></p>
                </div>
            <?php endif; ?>
            
            <?php if ($user_type === 'user' && !empty($reports)): ?>
                <div class="account-reports">
                    <h3>My Reports</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= escape($report->getId()) ?></td>
                                    <td><?= escape($report->getTitle()) ?></td>
                                    <td><?= escape($report->getDate()) ?></td>
                                    <td><?= escape($report->getStatus()) ?></td>
                                    <td>
                                        <a href="view_report.php?id=<?= $report->getId() ?>" class="btn btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p><a href="reportForm.php" class="btn">Report an Issue</a></p>
                </div>
            <?php elseif ($user_type === 'user'): ?>
                <div class="account-reports">
                    <h3>My Reports</h3>
                    <p>You haven't submitted any reports yet.</p>
                    <p><a href="reportForm.php" class="btn">Report an Issue</a></p>
                </div>
            <?php endif; ?>
            
            <?php if ($user_type === 'employee' && !empty($reports)): ?>
                <div class="account-reports">
                    <h3>Assigned Reports</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Submitted By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= escape($report->getId()) ?></td>
                                    <td><?= escape($report->getTitle()) ?></td>
                                    <td><?= escape($report->getDate()) ?></td>
                                    <td><?= escape($report->getStatus()) ?></td>
                                    <td><?= escape($report->getCustomerName()) ?></td>
                                    <td>
                                        <a href="view_report.php?id=<?= $report->getId() ?>" class="btn btn-sm">View</a>
                                        <a href="update_report.php?id=<?= $report->getId() ?>" class="btn btn-sm">Update</a>
                                    </td>
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
