<?php
/**
 * Get Products by Category
 * Fetches products filtered by category
 */

// Include database connection
include('connection.php');

// Get category from query parameter or default to 'all'
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Pagination
$products_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

try {
    if ($category === 'all') {
        // Get all products
        $stmt = $conn->prepare("
            SELECT * FROM products
            ORDER BY product_id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        // Count total products
        $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM products");
        $count_stmt->execute();
        $total_products = $count_stmt->fetch()['total'];

    } else {
        // Get products by category
        $stmt = $conn->prepare("
            SELECT * FROM products
            WHERE product_category = :category
            ORDER BY product_id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        // Count total products in category
        $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE product_category = :category");
        $count_stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $count_stmt->execute();
        $total_products = $count_stmt->fetch()['total'];
    }

    // Execute query
    $stmt->execute();

    // Fetch all products
    $products = $stmt->fetchAll();

    // Calculate total pages
    $total_pages = ceil($total_products / $products_per_page);

} catch (PDOException $e) {
    // Handle error
    $error_message = "Error fetching products: " . $e->getMessage();
    $products = [];
    $total_pages = 0;
}
?>
