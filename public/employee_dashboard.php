<?php
require_once '../includes/functions.php';
require_once '../src/DBconnect.php';
require_once '../includes/common.php';
require_once '../includes/classes/User.php';
require_once '../includes/classes/Employee.php';
require_once '../includes/classes/Submission.php';
require_once '../includes/classes/Report.php';
require_once '../includes/classes/SubmissionRepository.php';
require_once '../includes/classes/ReportRepository.php';
init_session();

// Check if user is logged in and is an employee
if (!isset($_SESSION['user_id']) || !is_employee()) {
    // Redirect to login page if not logged in or not an employee
    header('Location: login.php');
    exit;
}

$employee_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submission_action'])) {
        $submission_id = $_POST['submission_id'];
        $action = $_POST['action']; // 'approve' or 'reject'
        
        if (process_submission_action($submission_id, $action, $employee_id)) {
            $success_message = "Submission successfully " . ($action === 'approve' ? 'approved' : 'rejected');
            
            // Redirect to refresh the page and prevent form resubmission
            header("Location: employee_dashboard.php?tab=submissions&success=" . urlencode($success_message));
            exit;
        } else {
            $error_message = "Failed to process submission";
        }
    } elseif (isset($_POST['report_action'])) {
        $report_id = $_POST['report_id'];
        $action = $_POST['action']; // 'resolve' or 'reject'
        $resolution = $_POST['resolution'] ?? '';
        
        if (process_report_action($report_id, $action, $employee_id, $resolution)) {
            $success_message = "Report successfully " . ($action === 'resolve' ? 'resolved' : 'rejected');
            
            // Redirect to refresh the page and prevent form resubmission
            header("Location: employee_dashboard.php?tab=reports&success=" . urlencode($success_message));
            exit;
        } else {
            $error_message = "Failed to process report";
        }
    }
}

// Get success message from URL if it exists
if (isset($_GET['success'])) {
    $success_message = $_GET['success'];
}

// Get pending submissions and reports
$pending_submissions = get_pending_submissions();
$pending_reports = get_pending_reports();

// Get active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'submissions';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - TechTrade</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php echo generate_navbar('account'); ?>
    
    <main class="container">
        <section class="dashboard-section">
            <h1>Employee Dashboard</h1>
            
            <div style="text-align: right; margin-bottom: 20px;">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
            
            <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <div class="tabs">
                <ul class="tab-nav">
                    <li class="<?php echo $active_tab === 'submissions' ? 'active' : ''; ?>">
                        <a href="?tab=submissions">Submissions</a>
                    </li>
                    <li class="<?php echo $active_tab === 'reports' ? 'active' : ''; ?>">
                        <a href="?tab=reports">Reports</a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <?php if ($active_tab === 'submissions'): ?>
                        <div class="tab-pane active">
                            <h2>Pending Submissions (<?php echo count($pending_submissions); ?>)</h2>
                            
                            <?php if (empty($pending_submissions)): ?>
                                <p>No pending submissions to review.</p>
                            <?php else: ?>
                                <div class="submission-list">
                                    <?php foreach ($pending_submissions as $submission): ?>
                                        <div class="submission-item">
                                            <h3><?php echo escape($submission->getTitle()); ?></h3>
                                            <p><strong>From:</strong> <?php echo escape($submission->getCustomerName()); ?></p>
                                            <p><strong>Email:</strong> <?php echo escape($submission->getEmail()); ?></p>
                                            <p><strong>Category:</strong> <?php echo escape($submission->getCategory()); ?></p>
                                            <p><strong>Description:</strong> <?php echo escape($submission->getDescription()); ?></p>
                                            
                                            <?php if ($submission->getImages()): ?>
                                                <div class="submission-image">
                                                    <img src="uploads/items/<?php echo escape($submission->getImages()); ?>" alt="Submission Image" style="max-width: 300px; max-height: 200px;">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="submission-actions">
                                                <form method="post" action="employee_dashboard.php?tab=submissions">
                                                    <input type="hidden" name="submission_id" value="<?php echo $submission->getId(); ?>">
                                                    <input type="hidden" name="submission_action" value="1">
                                                    
                                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="tab-pane active">
                            <h2>Pending Reports (<?php echo count($pending_reports); ?>)</h2>
                            
                            <?php if (empty($pending_reports)): ?>
                                <p>No pending reports to review.</p>
                            <?php else: ?>
                                <div class="report-list">
                                    <?php foreach ($pending_reports as $report): ?>
                                        <div class="report-item">
                                            <h3><?php echo escape($report->getTitle()); ?></h3>
                                            <p><strong>From:</strong> <?php echo escape($report->getCustomerName()); ?></p>
                                            <p><strong>Date:</strong> <?php echo escape($report->getDate()); ?></p>
                                            <p><strong>Expectation:</strong> <?php echo escape($report->getExpectation()); ?></p>
                                            <p><strong>Description:</strong> <?php echo escape($report->getDescription()); ?></p>
                                            <p><strong>Technical Details:</strong> <?php echo escape($report->getTechnical()); ?></p>
                                            
                                            <?php if ($report->getItemImage()): ?>
                                                <div class="report-image">
                                                    <img src="uploads/reports/<?php echo escape($report->getItemImage()); ?>" alt="Item Image">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($report->getReceiptImage()): ?>
                                                <div class="report-image">
                                                    <img src="uploads/reports/<?php echo escape($report->getReceiptImage()); ?>" alt="Receipt Image">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="report-actions">
                                                <form method="post" action="employee_dashboard.php?tab=reports">
                                                    <input type="hidden" name="report_id" value="<?php echo $report->getId(); ?>">
                                                    <input type="hidden" name="report_action" value="1">
                                                    
                                                    <div class="form-group">
                                                        <label for="resolution">Resolution Notes:</label>
                                                        <textarea name="resolution" id="resolution" rows="3" class="form-control"></textarea>
                                                    </div>
                                                    
                                                    <button type="submit" name="action" value="resolve" class="btn btn-success">Resolve</button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> TechTrade. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
