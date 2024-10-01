<?php
session_start();
include '../includes/database.php';

$order_id = $_GET['id'];

// Hapus record dari tabel payments yang terkait dengan order
$payments_stmt = $pdo->prepare("DELETE FROM payments WHERE order_id = ?");
$payments_stmt->execute([$order_id]);

// Hapus order setelah pembayaran dihapus
$order_stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
$order_stmt->execute([$order_id]);

header("Location: view_orders.php");
exit;
