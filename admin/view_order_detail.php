<?php
session_start();
include '../includes/database.php';

// Ambil ID pesanan dari URL
$order_id = intval($_GET['id']); // Sanitize input to prevent SQL injection

// Ambil detail pesanan
$order_stmt = $pdo->prepare("SELECT o.*, u.name AS user_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$order_stmt->execute([$order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

// Check if order exists
if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Ambil item pesanan jika ada
$order_items_stmt = $pdo->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$order_items_stmt->execute([$order_id]);
$order_items = $order_items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5 text-center">Detail Pesanan</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Informasi Pesanan</h2>
            <p><strong>ID Pesanan:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
            <p><strong>Nama Pengguna:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
            <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        </div>

        <div class="mt-6 text-center">
            <a href="view_orders.php" class="text-blue-500 hover:underline">Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</body>

</html>