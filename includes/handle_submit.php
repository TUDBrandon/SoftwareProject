<?php
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
    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/../public/uploads/items/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique ID for this submission
    $item_id = uniqid();
    
    // Handle file upload
    $images = [];
    $image_name = $item_id . '_' . pathinfo($files['item_image']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($files['item_image']['tmp_name'], $upload_dir . $image_name);
    $images[] = $image_name;
    
    // Prepare submission data
    $submission = [
        'id' => $item_id,
        'timestamp' => time(),
        'customer_name' => $data['customer_name'],
        'email' => $data['email'],
        'title' => $data['title'],
        'category' => $data['category'],
        'description' => $data['description'],
        'images' => $images,
        'status' => 'pending'
    ];
    
    // Save to JSON file (temporary storage until we have a database)
    $submissions_file = __DIR__ . '/../data/submissions.json';
    $submissions = [];
    
    if (file_exists($submissions_file)) {
        $submissions = json_decode(file_get_contents($submissions_file), true) ?? [];
    }
    
    $submissions[] = $submission;
    
    // Create data directory if it doesn't exist
    $data_dir = dirname($submissions_file);
    if (!file_exists($data_dir)) {
        mkdir($data_dir, 0777, true);
    }
    
    file_put_contents($submissions_file, json_encode($submissions, JSON_PRETTY_PRINT));
    
    return $item_id;
}
