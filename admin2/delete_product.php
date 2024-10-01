<?php
session_start();
include '../includes/database.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Hapus produk dari database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);

    header('Location: dashboard.php');
    exit;
}
