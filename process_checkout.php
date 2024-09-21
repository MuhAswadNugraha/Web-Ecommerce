<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi informasi dari formulir
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Validasi input
    if (empty($name) || empty($email) || empty($address) || empty($phone)) {
        header("Location: error.php?message=Semua field harus diisi");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: error.php?message=Email tidak valid");
        exit;
    }

    // Simpan informasi pesanan ke database
    try {
        // Misalnya, ada tabel orders untuk menyimpan pesanan
        $stmt = $pdo->prepare("INSERT INTO orders (name, email, address, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $address, $phone]);

        // Ambil ID pesanan yang baru saja dimasukkan
        $order_id = $pdo->lastInsertId();

        // Simpan detail pesanan (produk dan jumlah) ke tabel order_items
        if (!empty($_SESSION['cart'])) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                if ($quantity > 0) { // Pastikan quantity valid
                    $stmt->execute([$order_id, $product_id, $quantity]);
                }
            }
        }

        // Kosongkan keranjang setelah pesanan berhasil
        unset($_SESSION['cart']);

        // Redirect ke halaman sukses
        header("Location: order_success.php?id=$order_id");
        exit;
    } catch (PDOException $e) {
        error_log('Failed to process checkout: ' . $e->getMessage());
        header("Location: error.php?message=Terjadi kesalahan saat memproses pesanan");
        exit;
    }
} else {
    header("Location: index.php"); // Redirect jika bukan request POST
    exit;
}
