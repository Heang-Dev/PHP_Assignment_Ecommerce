<?php
/**
 * Get Featured Products
 * Fetches products marked as special offers (featured products)
 */

// Include database connection
include('connection.php');

try {
    // Prepare SQL query to get featured products (special_offer = 1)
    $stmt = $conn->prepare("
        SELECT * FROM products
        WHERE product_special_offer = 1
        ORDER BY product_id ASC
        LIMIT 4
    ");

    // Execute query
    $stmt->execute();

    // Fetch all featured products
    $featured_products = $stmt->fetchAll();

} catch (PDOException $e) {
    // Handle error
    $error_message = "Error fetching featured products: " . $e->getMessage();
    $featured_products = [];
}
?>
