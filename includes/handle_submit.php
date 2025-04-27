<?php
require_once __DIR__ . '/../src/DBconnect.php';
require_once __DIR__ . '/common.php';

function validate_submit_form($data, $files) {
    $errors = [];
    
    // Validate required text fields
    $required_fields = ['customer_name', 'email', 'title', 'category', 'description'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    
    // Validate email
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    // Validate file uploads
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    // Check item images
    if (!isset($files['item_image']) || $files['item_image']['error'] !== 0) {
        $errors[] = 'At least one item image is required';
    } elseif (!in_array($files['item_image']['type'], $allowed_types)) {
        $errors[] = 'Item images must be JPG or PNG';
    } elseif ($files['item_image']['size'] > $max_size) {
        $errors[] = 'Each item image must be less than 5MB';
    }
    
    return $errors;
}

function save_submit($data, $files) {
    global $connection;
    
    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/../public/uploads/items/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Handle file upload
    $image_name = '';
    if (isset($files['item_image']) && $files['item_image']['error'] === 0) {
        $image_name = uniqid() . '_' . basename($files['item_image']['name']);
        move_uploaded_file($files['item_image']['tmp_name'], $upload_dir . $image_name);
    }
    
    try {
        // Get customer_id from session or from data
        $customer_id = isset($data['customer_id']) ? $data['customer_id'] : ($_SESSION['user_id'] ?? null);
        
        // Check if connection is valid
        if (!$connection || !($connection instanceof PDO)) {
            throw new Exception("Database connection is not available");
        }
        
        // Insert submission into database
        $stmt = $connection->prepare("
            INSERT INTO submission (username, email, title, category, description, images, status_update, customer_id)
            VALUES (:username, :email, :title, :category, :description, :images, 'Pending', :customer_id)
        ");
        
        $stmt->bindParam(':username', $data['customer_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':images', $image_name);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        // Return the ID of the newly inserted submission
        return $connection->lastInsertId();
        
    } catch (PDOException $e) {
        // Log the error
        error_log("Database error in save_submit: " . $e->getMessage());
        // Pass the actual error message to the Exception
        throw new Exception($e->getMessage());
    }
}
?>
