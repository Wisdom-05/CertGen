<?php
/**
 * Database connection for CertGen
 */

require_once __DIR__ . '/environment.php';

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'certgen';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    // For production, you might want to log this instead of dying with details
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed. Please check the logs.");
}

$conn->set_charset("utf8mb4");
?>
