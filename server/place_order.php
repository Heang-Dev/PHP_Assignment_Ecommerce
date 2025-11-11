<?php
/**
 * Place Order
 * Processes checkout and creates order in database
 */

session_start();

// Include database connection
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php?error=Please login to place an order');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    // Get cart total from session
    $order_cost = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Validate form data
    if (empty($name) || empty($email) || empty($phone) || empty($city) || empty($address)) {
        header('Location: ../checkout.php?error=All fields are required');
        exit();
    }

    // Validate cart is not empty
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || $order_cost <= 0) {
        header('Location: ../cart.php?error=Your cart is empty');
        exit();
    }

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Insert order into orders table
        $stmt = $conn->prepare("
            INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address)
            VALUES (:cost, :status, :user_id, :phone, :city, :address)
        ");

        $stmt->execute([
            ':cost' => $order_cost,
            ':status' => 'on_hold',
            ':user_id' => $user_id,
            ':phone' => $phone,
            ':city' => $city,
            ':address' => $address
        ]);

        // Get the order ID
        $order_id = $conn->lastInsertId();

        // Insert order items
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id)
            VALUES (:order_id, :product_id, :product_name, :product_image, :product_price, :product_quantity, :user_id)
        ");

        foreach ($_SESSION['cart'] as $product_id => $product) {
            $stmt->execute([
                ':order_id' => $order_id,
                ':product_id' => $product_id,
                ':product_name' => $product['product_name'],
                ':product_image' => $product['product_image'],
                ':product_price' => $product['product_price'],
                ':product_quantity' => $product['product_quantity'],
                ':user_id' => $user_id
            ]);
        }

        // Commit transaction
        $conn->commit();

        // Store order info in session for payment page
        $_SESSION['order_id'] = $order_id;
        $_SESSION['order_cost'] = $order_cost;
        $_SESSION['order_status'] = 'on_hold';

        // Redirect to payment page
        header('Location: ../payment.php');
        exit();

    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();

        // Redirect with error message
        header('Location: ../checkout.php?error=Error placing order. Please try again.');
        exit();
    }

} else {
    // If accessed directly without POST, redirect to checkout
    header('Location: ../checkout.php');
    exit();
}
?>
