<?php
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
    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/../public/uploads/reports/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique ID for this report
    $report_id = uniqid('report_');
    
    // Handle file uploads
    $item_image = $report_id . '_item.' . pathinfo($files['item_image']['name'], PATHINFO_EXTENSION);
    $receipt_image = $report_id . '_receipt.' . pathinfo($files['receipt_image']['name'], PATHINFO_EXTENSION);
    
    move_uploaded_file($files['item_image']['tmp_name'], $upload_dir . $item_image);
    move_uploaded_file($files['receipt_image']['tmp_name'], $upload_dir . $receipt_image);
    
    // Prepare report data
    $report = [
        'id' => $report_id,
        'timestamp' => time(),
        'customer_name' => $data['customer_name'],
        'employee_code' => $data['employee_code'],
        'title' => $data['title'],
        'date' => $data['date'],
        'expectation' => $data['expectation'],
        'description' => $data['description'],
        'technical' => $data['technical'],
        'item_image' => $item_image,
        'receipt_image' => $receipt_image,
        'status' => 'pending'
    ];
    
    // Save to JSON file (temporary storage until we have a database)
    $reports_file = __DIR__ . '/../data/reports.json';
    $reports = [];
    
    if (file_exists($reports_file)) {
        $reports = json_decode(file_get_contents($reports_file), true) ?? [];
    }
    
    $reports[] = $report;
    
    // Create data directory if it doesn't exist
    $data_dir = dirname($reports_file);
    if (!file_exists($data_dir)) {
        mkdir($data_dir, 0777, true);
    }
    
    file_put_contents($reports_file, json_encode($reports, JSON_PRETTY_PRINT));
    
    return $report_id;
}
