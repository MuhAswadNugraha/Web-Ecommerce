<?php
session_start();
include 'includes/database.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id) {
    // Ambil detail pesanan dari database
    $stmt = $pdo->prepare("SELECT o.*, u.name, u.email, u.address, u.phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Pesanan tidak ditemukan. Silakan kembali ke beranda dan coba lagi.");
    }

    // Ambil detail barang yang dipesan
    $stmt = $pdo->prepare("SELECT p.name AS product_name, oi.quantity, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
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
        <p>Terima kasih, <?php echo htmlspecialchars($order['name']); ?>. Pesanan Anda telah berhasil diproses.</p>
        <h2 class="font-semibold mt-4">Detail Pesanan:</h2>
        <p>Nama: <?php echo htmlspecialchars($order['name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
        <p>Alamat: <?php echo htmlspecialchars($order['address']); ?></p>
        <p>Telepon: <?php echo htmlspecialchars($order['phone']); ?></p>

        <h2 class="font-semibold mt-4">Barang yang Dipesan:</h2>
        <table class="min-w-full bg-white border border-gray-300 mt-4">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nama Barang</th>
                    <th class="py-2 px-4 border-b">Jumlah</th>
                    <th class="py-2 px-4 border-b">Harga</th>
                    <th class="py-2 px-4 border-b">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_amount = 0; ?>
                <?php foreach ($order_items as $item):
                    $item_total = $item['price'] * $item['quantity'];
                    $total_amount += $item_total;
                ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td class="py-2 px-4 border-b">Rp <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                        <td class="py-2 px-4 border-b">Rp <?php echo number_format($item_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="font-bold mt-4">Total Pembayaran: Rp <?php echo number_format($total_amount, 2, ',', '.'); ?></p>
        <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Beranda</a>
    </div>
</body>

</html>