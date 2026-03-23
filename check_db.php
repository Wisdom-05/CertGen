<?php
require_once 'config/database.php';
$result = $conn->query("DESCRIBE certificate_logs");
if ($result) {
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing table: " . $conn->error;
}
?>
