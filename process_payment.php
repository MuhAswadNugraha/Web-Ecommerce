<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $order_id = $_SESSION['user_id'];
    $total_price = array_sum(array_map(function ($item) {
        return $item['product']['price'] * $item['quantity'];
    }, $_SESSION['cart']));

    // Insert payment information into the database
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->execute([$order_id, $total_price, $payment_method]);

    // Simulate payment success (update status later based on actual payment success/failure)
    $payment_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("UPDATE payments SET status = 'completed' WHERE id = ?");
    $stmt->execute([$payment_id]);

    // Clear cart after successful payment
    unset($_SESSION['cart']);
    unset($_SESSION['order_id']);

    // Redirect to order success page
    header("Location: order_success.php?id=" . $order_id);
    exit;
}
