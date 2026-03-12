<?php
/**
 * auth.php - Session management and authentication checks
 */
session_start();

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login for a page
 */
function require_login() {
    if (!is_logged_in()) {
        // Find path to login.php
        $path_prefix = file_exists('login.php') ? '' : (file_exists('../login.php') ? '../' : '../../');
        header("Location: {$path_prefix}login.php");
        exit();
    }
}

/**
 * Get current logged in user details
 */
function get_user() {
    return $_SESSION['user_name'] ?? 'Guest';
}
?>
