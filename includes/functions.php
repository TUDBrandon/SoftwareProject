<?php
function create_form_field($name, $label, $type = 'text', $required = true, $value = '') {
    $required_attr = $required ? 'required' : '';
    $html = <<<HTML
    <div class="form-group">
        <label for="$name">$label</label>
        <input type="$type" name="$name" id="$name" value="$value" $required_attr>
    </div>
    HTML;
    
    return $html;
}

function display_product_carousel($products, $category_id) {
    $html = '<div class="carousel-container" id="' . $category_id . '-carousel">';
    $html .= '<div class="carousel-track">';
    
    foreach ($products as $product) {
        // Fix image path - if it starts with 'public/', remove it
        $image = $product['image'];
        if (strpos($image, 'public/') === 0) {
            $image = substr($image, 7); // Remove 'public/' prefix
        }
        
        $html .= '<div class="carousel-item">';
        $html .= '<a href="' . $product['link'] . '" class="product-link">';
        $html .= '<div class="product-card">';
        $html .= '<img src="' . $image . '" alt="' . $product['name'] . '">';
        $html .= '<h3>' . $product['name'] . '</h3>';
        $html .= '<p class="price">$' . number_format($product['price'], 2) . '</p>';
        $html .= '<button class="buy-now">View Details</button>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '<button class="carousel-prev" aria-label="Previous">&#10094;</button>';
    $html .= '<button class="carousel-next" aria-label="Next">&#10095;</button>';
    $html .= '</div>';
    
    return $html;
}

function get_hardware_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Hardware'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving hardware products: " . $e->getMessage());
        return [];
    }
}

function get_console_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Consoles'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving console products: " . $e->getMessage());
        return [];
    }
}

function get_phone_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Phones'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving phone products: " . $e->getMessage());
        return [];
    }
}

function get_game_products() {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products WHERE category = 'Games'";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving game products: " . $e->getMessage());
        return [];
    }
}

function include_carousel_js() {
    $timestamp = time();
    echo '<script src="../includes/js/carousel.js?v=' . $timestamp . '"></script>';
}

/**
 * Get bestselling products
 * 
 * @param int $limit Number of products to return
 * @return array Array of bestselling products
 */
function get_bestselling_products($limit = 5) {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        if (!$connection) {
            throw new Exception("Database connection failed");
        }
        
        $sql = "SELECT * FROM products 
                ORDER BY RAND() 
                LIMIT :limit";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert database column names to match the expected format
        $formatted_results = [];
        foreach ($result as $product) {
            // Fix image path - if it starts with 'public/', remove it
            $image = $product['image'] ?? $product['image_path'] ?? '';
            if (strpos($image, 'public/') === 0) {
                $image = substr($image, 7); // Remove 'public/' prefix
            }
            
            $formatted_results[] = [
                'name' => $product['name'] ?? $product['product_name'] ?? '',
                'price' => $product['price'] ?? 0,
                'image' => $image,
                'category' => $product['category'] ?? '',
                'link' => $product['link'] ?? $product['product_link'] ?? '#'
            ];
        }
        
        return $formatted_results;
    } catch (Exception $e) {
        error_log("Error retrieving bestselling products: " . $e->getMessage());
        return [];
    }
}

/**
 * Process employee action on a submission
 * 
 * @param int $submission_id The submission ID
 * @param string $action Either 'approve' or 'reject'
 * @param int $employee_id The employee ID
 * @return bool Success or failure
 */
function process_submission_action($submission_id, $action, $employee_id) {
    try {
        require_once __DIR__ . '/classes/User.php';
        require_once __DIR__ . '/classes/Submission.php';
        require_once __DIR__ . '/classes/SubmissionRepository.php';
        require_once __DIR__ . '/classes/Employee.php';
        
        $repo = new SubmissionRepository();
        $submission = $repo->getSubmissionById($submission_id);
        
        if (!$submission) {
            return false;
        }
        
        $employee = new Employee(['id' => $employee_id]);
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        
        // Update the submission object
        if ($employee->manageSubmission($submission, $status)) {
            // Save the updated submission to the database
            return $repo->updateSubmissionStatus($submission_id, $status);
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Error processing submission action: " . $e->getMessage());
        return false;
    }
}

/**
 * Process employee action on a report
 * 
 * @param int $report_id The report ID
 * @param string $action Either 'resolve' or 'reject'
 * @param int $employee_id The employee ID
 * @param string $resolution Optional resolution notes
 * @return bool Success or failure
 */
function process_report_action($report_id, $action, $employee_id, $resolution = '') {
    try {
        require_once __DIR__ . '/classes/User.php';
        require_once __DIR__ . '/classes/Report.php';
        require_once __DIR__ . '/classes/ReportRepository.php';
        require_once __DIR__ . '/classes/Employee.php';
        
        $repo = new ReportRepository();
        $report = $repo->getReportById($report_id);
        
        if (!$report) {
            return false;
        }
        
        $employee = new Employee(['id' => $employee_id]);
        $status = ($action === 'resolve') ? 'Resolved' : 'Rejected';
        
        // Update the report object
        if ($employee->handleReport($report, $action)) {
            // Save the updated report status to the database
            return $repo->updateReportStatus($report_id, $status);
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Error processing report action: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all pending submissions
 * 
 * @return array Array of pending submissions
 */
function get_pending_submissions() {
    try {
        require_once __DIR__ . '/classes/Submission.php';
        require_once __DIR__ . '/classes/SubmissionRepository.php';
        
        $repo = new SubmissionRepository();
        return $repo->getSubmissionsByStatus('Pending');
    } catch (Exception $e) {
        error_log("Error getting pending submissions: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all pending reports
 * 
 * @return array Array of pending reports
 */
function get_pending_reports() {
    try {
        require_once __DIR__ . '/classes/Report.php';
        require_once __DIR__ . '/classes/ReportRepository.php';
        
        $repo = new ReportRepository();
        return $repo->getReportsByStatus('Pending');
    } catch (Exception $e) {
        error_log("Error getting pending reports: " . $e->getMessage());
        return [];
    }
}

/**
 * Check if a user is an employee by querying the database
 * 
 * @param int $user_id The user ID
 * @return bool True if user is an employee, false otherwise
 */
function check_employee_role($user_id) {
    try {
        require_once __DIR__ . '/../src/DBconnect.php';
        global $connection;
        
        $stmt = $connection->prepare("SELECT role FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $role = $stmt->fetchColumn();
        
        return $role === 'employee';
    } catch (Exception $e) {
        error_log("Error checking if user is employee: " . $e->getMessage());
        return false;
    }
}
?>
