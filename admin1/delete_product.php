<?php
session_start();
include '../includes/database.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Cek apakah produk ada sebelum menghapus
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Hapus entri terkait di order_items
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE product_id = ?");
        $stmt->execute([$product_id]);

        // Hapus produk dari database
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        // Redirect ke dashboard dengan pesan sukses
        header('Location: view_product.php?message=Produk berhasil dihapus');
        exit;
    } else {
        // Redirect ke dashboard dengan pesan kesalahan
        header('Location: dashboard.php?error=Produk tidak ditemukan');
        exit;
    }
} else {
    // Redirect ke dashboard jika tidak ada ID
    header('Location: dashboard.php?error=ID tidak valid');
    exit;
}
