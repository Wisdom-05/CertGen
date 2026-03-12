<?php
/**
 * Database connection for CertGen
 */

require_once __DIR__ . '/environment.php';

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'certgen';
$db_port = getenv('DB_PORT') ?: '3306';

$conn = mysqli_init();

// If we are on a custom port (like Aiven's 11655), enable SSL
if (getenv('DB_PORT') && getenv('DB_PORT') !== '3306') {
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
}

// Connect with a timeout
$success = $conn->real_connect($db_host, $db_user, $db_pass, $db_name, (int)$db_port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    error_log("Database connection failed: " . mysqli_connect_error());
    // On Render, we want to see the error for debugging
    die("Database connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");
?>
