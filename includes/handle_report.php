<?php
require_once __DIR__ . '/../src/DBconnect.php';
require_once __DIR__ . '/common.php';

function validate_report_form($data, $files) {
    $errors = [];
    
    // Validate required text fields
    $required_fields = ['customer_name', 'employee_code', 'title', 'date', 'expectation', 'description', 'technical'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    
    // Validate date
    if (!empty($data['date'])) {
        $purchase_date = strtotime($data['date']);
        if ($purchase_date > time()) {
            $errors[] = 'Purchase date cannot be in the future';
        }
    }
    
    // Validate file uploads
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    // Check item image
    if (!isset($files['item_image']) || $files['item_image']['error'] !== 0) {
        $errors[] = 'Item image is required';
    } elseif (!in_array($files['item_image']['type'], $allowed_types)) {
        $errors[] = 'Item image must be JPG or PNG';
    } elseif ($files['item_image']['size'] > $max_size) {
        $errors[] = 'Item image must be less than 5MB';
    }
    
    // Check receipt image
    if (!isset($files['receipt_image']) || $files['receipt_image']['error'] !== 0) {
        $errors[] = 'Receipt image is required';
    } elseif (!in_array($files['receipt_image']['type'], $allowed_types)) {
        $errors[] = 'Receipt image must be JPG or PNG';
    } elseif ($files['receipt_image']['size'] > $max_size) {
        $errors[] = 'Receipt image must be less than 5MB';
    }
    
    return $errors;
}

function save_report($data, $files) {
    global $connection;
    
    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/../public/uploads/reports/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Handle file uploads
    $item_image_name = '';
    $receipt_image_name = '';
    
    if (isset($files['item_image']) && $files['item_image']['error'] === 0) {
        $item_image_name = uniqid() . '_' . basename($files['item_image']['name']);
        move_uploaded_file($files['item_image']['tmp_name'], $upload_dir . $item_image_name);
    }
    
    if (isset($files['receipt_image']) && $files['receipt_image']['error'] === 0) {
        $receipt_image_name = uniqid() . '_' . basename($files['receipt_image']['name']);
        move_uploaded_file($files['receipt_image']['tmp_name'], $upload_dir . $receipt_image_name);
    }
    
    try {
        // Find employee ID based on employee code
        $stmt = $connection->prepare("SELECT employee_id FROM employees WHERE employee_id = :employee_code");
        $stmt->bindParam(':employee_code', $data['employee_code']);
        $stmt->execute();
        $employee = $stmt->fetch();
        
        $employee_id = $employee ? $employee['employee_id'] : null;
        
        // Format date for database
        $date = date('Y-m-d H:i:s', strtotime($data['date']));
        
        // Get customer_id from session or from data
        $customer_id = isset($data['customer_id']) ? $data['customer_id'] : ($_SESSION['user_id'] ?? null);
        
        // Insert report into database
        $stmt = $connection->prepare("
            INSERT INTO report (
                username, title, date, expectation, description, 
                technical, item_image, receipt_image, status_update, employee_id, customer_id
            ) VALUES (
                :username, :title, :date, :expectation, :description, 
                :technical, :item_image, :receipt_image, 'Pending', :employee_id, :customer_id
            )
        ");
        
        $stmt->bindParam(':username', $data['customer_name']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':expectation', $data['expectation']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':technical', $data['technical']);
        $stmt->bindParam(':item_image', $item_image_name);
        $stmt->bindParam(':receipt_image', $receipt_image_name);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        // Return the ID of the newly inserted report
        return $connection->lastInsertId();
        
    } catch (PDOException $e) {
        // Log the error
        error_log("Database error in save_report: " . $e->getMessage());
        throw new Exception("Failed to save report: " . $e->getMessage());
    }
}
?>
