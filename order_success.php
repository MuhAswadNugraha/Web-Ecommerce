<?php
session_start();
include 'includes/database.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id) {
    // Ambil detail pesanan dari database
    $stmt = $pdo->prepare("SELECT o.*, u.fullname, u.email, u.address, u.phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Pesanan tidak ditemukan. Silakan kembali ke beranda dan coba lagi.");
    }

    // Ambil detail barang yang dipesan
    $stmt = $pdo->prepare("SELECT p.name AS product_name, oi.quantity, p.price, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("ID pesanan tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Pesanan Berhasil!</h1>
        <p class="text-lg">Terima kasih, <span class="font-bold"><?php echo htmlspecialchars($order['fullname']); ?></span>. Pesanan Anda telah berhasil diproses.</p>
        <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Beranda</a>
    </div>
</body>

</html>