<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    // Check if cart is set and is an array
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        die("Keranjang tidak ditemukan.");
    }

    // Calculate total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Fetch product details
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total_price += $product['price'] * $quantity;
        }
    }

    // Create a new order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id,  status, created_at) VALUES (?, 'pending', NOW())");
    if (!$stmt->execute([$user_id,])) {
        die("Error creating order: " . implode(", ", $stmt->errorInfo()));
    }
    $order_id = $pdo->lastInsertId();

    // Save payment information
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    if (!$stmt->execute([$order_id, $total_price, $payment_method])) {
        die("Error saving payment: " . implode(", ", $stmt->errorInfo()));
    }

    // Notify user
    $notification_stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
    $message = "Pembayaran untuk pesanan ID " . $order_id . " berhasil. Silakan berikan rating dan ulasan.";
    $notification_stmt->execute([$user_id, $message]);

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: order_success.php?id=" . $order_id);
    exit;
}
