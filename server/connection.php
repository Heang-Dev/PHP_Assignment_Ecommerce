<?php
/**
 * Database Connection File
 * Establishes PDO connection to SQLite database
 */

// Define database path
$db_path = __DIR__ . '/../database/ecommerce.db';

// Check if database file exists
if (!file_exists($db_path)) {
    die("Database file not found. Please run database/create_database.php first.");
}

try {
    // Create PDO connection to SQLite database
    $conn = new PDO('sqlite:' . $db_path);

    // Set error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set fetch mode to associative array by default
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
