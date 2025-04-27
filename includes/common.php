<?php
/**
 * Common functions and utilities for the TechTrade application
 */

// Initialize session if not already started
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Start session automatically when common.php is included
init_session();

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

/**
 * Generate a standardized navigation bar for the site
 * 
 * @param string $active_page The current page to highlight in the navigation
 * @return string HTML for the navigation bar
 */
function generate_navbar($active_page = '') {
    // Determine active class for each link
    $buy_active = ($active_page === 'buy') ? 'active' : '';
    $sell_active = ($active_page === 'sell') ? 'active' : '';
    $account_active = ($active_page === 'account') ? 'active' : '';
    $contact_active = ($active_page === 'contact') ? 'active' : '';
    
    $html = <<<HTML
    <header>
        <nav class="main-nav">
            <div class="nav-left">
                <a href="index.php" class="logo">TechTrade</a>
                <ul class="nav-links">
                    <li class="dropdown-parent">
                        <a href="browse.php" class="$buy_active">Buy</a>
                        <ul class="dropdown">
                            <li><a href="browse.php?category=hardware#hardware">Hardware</a></li>
                            <li><a href="browse.php?category=consoles#consoles">Consoles</a></li>
                            <li><a href="browse.php?category=phones#phones">Phones</a></li>
                            <li><a href="browse.php?category=games#games">Games</a></li>
                        </ul>
                    </li>
                    <li><a href="submitForm.php" class="$sell_active">Sell</a></li>
                    <li><a href="account.php" class="$account_active">Account</a></li>
                    <li><a href="contactUs.php" class="$contact_active">Contact Us</a></li>
                </ul>
            </div>
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="search" name="q" placeholder="Search for games, phones, tech...">
                    <button type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>
HTML;
    
    return $html;
}
?>
