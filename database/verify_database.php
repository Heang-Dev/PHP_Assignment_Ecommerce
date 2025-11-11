<?php
/**
 * Database Verification Script
 * Checks database contents and displays statistics
 */

// Database file path
$db_path = __DIR__ . '/ecommerce.db';

try {
    // Connect to database
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== DATABASE VERIFICATION ===\n\n";

    // Check products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $product_count = $stmt->fetch()['count'];
    echo "✓ Total Products: $product_count\n";

    // Check products by category
    $categories = ['shoes', 'bags', 'hats', 'watches'];
    foreach ($categories as $category) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE product_category = :category");
        $stmt->execute([':category' => $category]);
        $count = $stmt->fetch()['count'];
        echo "  - $category: $count products\n";
    }

    // Check featured products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE product_special_offer = 1");
    $featured_count = $stmt->fetch()['count'];
    echo "  - Featured (special offer): $featured_count products\n\n";

    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $user_count = $stmt->fetch()['count'];
    echo "✓ Total Users: $user_count\n";

    // Display test user
    $stmt = $pdo->query("SELECT user_email FROM users LIMIT 1");
    $test_user = $stmt->fetch();
    if ($test_user) {
        echo "  - Test User: {$test_user['user_email']} (password: password123)\n\n";
    }

    // Check orders
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $order_count = $stmt->fetch()['count'];
    echo "✓ Total Orders: $order_count\n";

    // Check order items
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM order_items");
    $order_item_count = $stmt->fetch()['count'];
    echo "✓ Total Order Items: $order_item_count\n\n";

    // Display some sample products
    echo "=== SAMPLE PRODUCTS ===\n\n";
    $stmt = $pdo->query("SELECT product_id, product_name, product_category, product_price FROM products LIMIT 5");
    $products = $stmt->fetchAll();
    foreach ($products as $product) {
        echo sprintf("ID: %d | %s | %s | $%.2f\n",
            $product['product_id'],
            $product['product_name'],
            $product['product_category'],
            $product['product_price']
        );
    }

    echo "\n✅ Database verification completed successfully!\n";
    echo "\nYou can now test the application by visiting index.php in your browser.\n";
    echo "Test credentials: test@example.com / password123\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
