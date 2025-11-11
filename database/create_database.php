<?php
/**
 * Database Creation Script for E-commerce Project
 * This script creates the SQLite database and all necessary tables
 */

// Database file path
$db_path = __DIR__ . '/ecommerce.db';

// Remove existing database if it exists (for fresh start)
if (file_exists($db_path)) {
    unlink($db_path);
    echo "Existing database removed.\n";
}

try {
    // Create new SQLite database connection
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "SQLite database created successfully.\n";

    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_name VARCHAR(255) NOT NULL,
            user_email VARCHAR(255) NOT NULL UNIQUE,
            user_password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Table 'users' created successfully.\n";

    // Create products table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            product_id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_name VARCHAR(255) NOT NULL,
            product_category VARCHAR(100) NOT NULL,
            product_description TEXT,
            product_image VARCHAR(255) NOT NULL,
            product_image2 VARCHAR(255),
            product_image3 VARCHAR(255),
            product_image4 VARCHAR(255),
            product_price DECIMAL(10,2) NOT NULL,
            product_special_offer INTEGER DEFAULT 0,
            product_color VARCHAR(100),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Table 'products' created successfully.\n";

    // Create orders table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            order_id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_cost DECIMAL(10,2) NOT NULL,
            order_status VARCHAR(50) NOT NULL DEFAULT 'on_hold',
            user_id INTEGER NOT NULL,
            user_phone VARCHAR(20) NOT NULL,
            user_city VARCHAR(100) NOT NULL,
            user_address TEXT NOT NULL,
            order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )
    ");
    echo "Table 'orders' created successfully.\n";

    // Create order_items table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            item_id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            product_image VARCHAR(255) NOT NULL,
            product_price DECIMAL(10,2) NOT NULL,
            product_quantity INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(order_id),
            FOREIGN KEY (product_id) REFERENCES products(product_id),
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )
    ");
    echo "Table 'order_items' created successfully.\n";

    // Insert sample products - Featured Shoes
    $products = [
        // Featured Shoes (4 products)
        [
            'name' => 'Premium Sneakers',
            'category' => 'shoes',
            'description' => 'Comfortable and stylish premium sneakers perfect for everyday wear. Made with high-quality materials for maximum durability and comfort.',
            'image' => 'featured_1.png',
            'image2' => 'featured_1.png',
            'image3' => 'featured_2.png',
            'image4' => 'featured_3.png',
            'price' => 89.99,
            'special_offer' => 1,
            'color' => 'White'
        ],
        [
            'name' => 'Sport Running Shoes',
            'category' => 'shoes',
            'description' => 'High-performance running shoes designed for athletes. Lightweight construction with superior cushioning for long-distance running.',
            'image' => 'featured_2.png',
            'image2' => 'featured_2.png',
            'image3' => 'featured_3.png',
            'image4' => 'featured_4.png',
            'price' => 129.99,
            'special_offer' => 1,
            'color' => 'Blue'
        ],
        [
            'name' => 'Classic Canvas Shoes',
            'category' => 'shoes',
            'description' => 'Timeless canvas shoes that never go out of style. Perfect for casual outings and everyday comfort.',
            'image' => 'featured_3.png',
            'image2' => 'featured_3.png',
            'image3' => 'featured_4.png',
            'image4' => 'featured_1.png',
            'price' => 59.99,
            'special_offer' => 1,
            'color' => 'Black'
        ],
        [
            'name' => 'Urban Street Shoes',
            'category' => 'shoes',
            'description' => 'Modern street-style shoes with contemporary design. Combines fashion and functionality for urban lifestyle.',
            'image' => 'featured_4.png',
            'image2' => 'featured_4.png',
            'image3' => 'featured_1.png',
            'image4' => 'featured_2.png',
            'price' => 99.99,
            'special_offer' => 1,
            'color' => 'Gray'
        ],

        // Bags & Backpacks (4 products)
        [
            'name' => 'Executive Leather Bag',
            'category' => 'bags',
            'description' => 'Premium leather bag perfect for professionals. Multiple compartments for laptop, documents, and accessories.',
            'image' => 'bag_1.png',
            'image2' => 'bag_1.png',
            'image3' => 'bag_2.png',
            'image4' => 'bag_3.png',
            'price' => 159.99,
            'special_offer' => 0,
            'color' => 'Brown'
        ],
        [
            'name' => 'Adventure Backpack',
            'category' => 'bags',
            'description' => 'Durable backpack designed for outdoor adventures. Water-resistant material with ergonomic design.',
            'image' => 'bag_2.png',
            'image2' => 'bag_2.png',
            'image3' => 'bag_3.png',
            'image4' => 'bag_4.png',
            'price' => 79.99,
            'special_offer' => 0,
            'color' => 'Black'
        ],
        [
            'name' => 'Student Campus Bag',
            'category' => 'bags',
            'description' => 'Spacious and comfortable backpack ideal for students. Padded laptop sleeve and organized pockets.',
            'image' => 'bag_3.png',
            'image2' => 'bag_3.png',
            'image3' => 'bag_4.png',
            'image4' => 'bag_1.png',
            'price' => 49.99,
            'special_offer' => 0,
            'color' => 'Navy Blue'
        ],
        [
            'name' => 'Travel Duffel Bag',
            'category' => 'bags',
            'description' => 'Versatile duffel bag perfect for weekend getaways. Lightweight yet spacious with sturdy handles.',
            'image' => 'bag_4.png',
            'image2' => 'bag_4.png',
            'image3' => 'bag_1.png',
            'image4' => 'bag_2.png',
            'price' => 89.99,
            'special_offer' => 0,
            'color' => 'Gray'
        ],

        // Hats & Caps (4 products)
        [
            'name' => 'Classic Baseball Cap',
            'category' => 'hats',
            'description' => 'Timeless baseball cap design. Adjustable strap for perfect fit. Perfect for sunny days.',
            'image' => 'hat_1.png',
            'image2' => 'hat_1.png',
            'image3' => 'hat_2.png',
            'image4' => 'hat_3.png',
            'price' => 24.99,
            'special_offer' => 0,
            'color' => 'Black'
        ],
        [
            'name' => 'Summer Bucket Hat',
            'category' => 'hats',
            'description' => 'Trendy bucket hat perfect for summer. Provides excellent sun protection with stylish design.',
            'image' => 'hat_2.png',
            'image2' => 'hat_2.png',
            'image3' => 'hat_3.png',
            'image4' => 'hat_4.png',
            'price' => 29.99,
            'special_offer' => 0,
            'color' => 'Beige'
        ],
        [
            'name' => 'Snapback Street Cap',
            'category' => 'hats',
            'description' => 'Modern snapback cap with urban street style. Flat brim design with premium embroidery.',
            'image' => 'hat_3.png',
            'image2' => 'hat_3.png',
            'image3' => 'hat_4.png',
            'image4' => 'hat_1.png',
            'price' => 34.99,
            'special_offer' => 0,
            'color' => 'White'
        ],
        [
            'name' => 'Winter Beanie',
            'category' => 'hats',
            'description' => 'Warm and cozy beanie for cold weather. Soft knit material provides comfort and warmth.',
            'image' => 'hat_4.png',
            'image2' => 'hat_4.png',
            'image3' => 'hat_1.png',
            'image4' => 'hat_2.png',
            'price' => 19.99,
            'special_offer' => 0,
            'color' => 'Gray'
        ],

        // Watches (4 products)
        [
            'name' => 'Luxury Steel Watch',
            'category' => 'watches',
            'description' => 'Premium stainless steel watch with precision movement. Water-resistant and elegant design.',
            'image' => 'watch_1.png',
            'image2' => 'watch_1.png',
            'image3' => 'watch_2.png',
            'image4' => 'watch_3.png',
            'price' => 299.99,
            'special_offer' => 0,
            'color' => 'Silver'
        ],
        [
            'name' => 'Smart Fitness Watch',
            'category' => 'watches',
            'description' => 'Advanced smartwatch with fitness tracking. Heart rate monitor, GPS, and smartphone connectivity.',
            'image' => 'watch_2.png',
            'image2' => 'watch_2.png',
            'image3' => 'watch_3.png',
            'image4' => 'watch_4.png',
            'price' => 249.99,
            'special_offer' => 0,
            'color' => 'Black'
        ],
        [
            'name' => 'Classic Analog Watch',
            'category' => 'watches',
            'description' => 'Timeless analog watch with leather strap. Simple elegance for everyday wear.',
            'image' => 'watch_3.png',
            'image2' => 'watch_3.png',
            'image3' => 'watch_4.png',
            'image4' => 'watch_1.png',
            'price' => 149.99,
            'special_offer' => 0,
            'color' => 'Brown'
        ],
        [
            'name' => 'Sport Chronograph',
            'category' => 'watches',
            'description' => 'Sporty chronograph watch with multiple functions. Durable and stylish for active lifestyle.',
            'image' => 'watch_4.png',
            'image2' => 'watch_4.png',
            'image3' => 'watch_1.png',
            'image4' => 'watch_2.png',
            'price' => 199.99,
            'special_offer' => 0,
            'color' => 'Blue'
        ]
    ];

    // Prepare insert statement
    $stmt = $pdo->prepare("
        INSERT INTO products
        (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_special_offer, product_color)
        VALUES
        (:name, :category, :description, :image, :image2, :image3, :image4, :price, :special_offer, :color)
    ");

    // Insert all products
    foreach ($products as $product) {
        $stmt->execute([
            ':name' => $product['name'],
            ':category' => $product['category'],
            ':description' => $product['description'],
            ':image' => $product['image'],
            ':image2' => $product['image2'],
            ':image3' => $product['image3'],
            ':image4' => $product['image4'],
            ':price' => $product['price'],
            ':special_offer' => $product['special_offer'],
            ':color' => $product['color']
        ]);
    }

    echo "Successfully inserted " . count($products) . " products.\n";

    // Create a test user (optional)
    $test_password = password_hash('password123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (user_name, user_email, user_password)
        VALUES ('Test User', 'test@example.com', '$test_password')
    ");
    echo "Test user created (email: test@example.com, password: password123).\n";

    echo "\n✅ Database setup completed successfully!\n";
    echo "Database location: $db_path\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
