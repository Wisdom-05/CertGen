require_once 'includes/auth.php';
require_once 'config/database.php';
require_login();

echo "<h1>Database Connection Diagnostic</h1>";

if (isset($conn) && $conn->ping()) {
    echo "<div style='color: green; font-weight: bold; padding: 20px; border: 2px solid green; border-radius: 8px; background: #e6fffa;'>";
    echo "✅ Success! The application can connect to the database.<br>";
    echo "Host: " . htmlspecialchars(getenv('DB_HOST') ?: 'localhost') . "<br>";
    echo "Database: " . htmlspecialchars(getenv('DB_NAME') ?: 'certgen');
    echo "</div>";

    // Check if tables exist
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "<h3>Tables found:</h3><ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . htmlspecialchars($row[0]) . "</li>";
        }
        echo "</ul>";
        if ($result->num_rows == 0) {
            echo "<p style='color: orange;'>⚠️ No tables found. Did you run the setup_database.sql script?</p>";
        }
    }
}
else {
    echo "<div style='color: red; font-weight: bold; padding: 20px; border: 2px solid red; border-radius: 8px; background: #fff5f5;'>";
    echo "❌ Connection Failed!<br>";
    if (isset($conn) && $conn->connect_error) {
        echo "Error: " . htmlspecialchars($conn->connect_error);
    }
    else {
        echo "The database connection could not be established.";
    }
    echo "</div>";

    echo "<h3>Debugging Tips:</h3>";
    echo "<ul>";
    echo "<li>Check your Render Environment Variables (DB_HOST, DB_USER, etc.)</li>";
    echo "<li>Ensure your database service is running</li>";
    echo "<li>If using a remote database, ensure it allows connections from Render's IP range</li>";
    echo "</li>";
}
?>
