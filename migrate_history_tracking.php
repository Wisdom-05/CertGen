<?php
require_once 'config/database.php';

$sql = "ALTER TABLE certificate_logs ADD COLUMN generated_by INT DEFAULT NULL, ADD CONSTRAINT fk_user_logs FOREIGN KEY (generated_by) REFERENCES users(id)";
if ($conn->query($sql)) {
    echo "Column 'generated_by' added and foreign key created successfully.\n";
} else {
    echo "Error updating table: " . $conn->error . "\n";
}
?>
