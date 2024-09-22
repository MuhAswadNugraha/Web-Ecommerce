<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan ada keranjang
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        die("Cart is empty. Please add items to your cart before proceeding to checkout.");
    }

    // Ambil data dari form
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Buat order baru di database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, created_at) VALUES (?, NOW())");
    $stmt->execute([$_SESSION['user_id']]);
    $order_id = $pdo->lastInsertId();

    // Ambil item keranjang
    $cart_items = $_SESSION['cart'];
    $total_price = 0;

    // Ambil detail produk dari database berdasarkan product_id
    foreach ($cart_items as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Tambahkan item ke order_items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity]);

            // Hitung total harga
            $total_price += $product['price'] * $quantity;
        }
    }

    // Simpan informasi pesanan di session untuk pembayaran
    $_SESSION['order_id'] = $order_id;
    $_SESSION['total_price'] = $total_price;

    // Redirect ke halaman pembayaran
    header("Location: payment.php");
    exit;
}
