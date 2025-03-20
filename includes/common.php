<?php
/**
 * Common functions and utilities for the TechTrade application
 */

// Escape HTML output to prevent XSS
function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user has admin role
function is_admin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Check if user has employee role
function is_employee() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employee';
}

// Redirect to another page
function redirect($location) {
    header("Location: $location");
    exit;
}

// Flash messages between pages
function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Format date for display
function format_date($date) {
    return date('F j, Y', strtotime($date));
}
?>
