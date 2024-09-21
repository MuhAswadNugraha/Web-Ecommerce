<?php
session_start();
include 'includes/database.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id) {
    // Ambil detail pesanan dari database
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <p>Detail Pesanan:</p>
        <p>Nama: <?php echo htmlspecialchars($order['name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
        <p>Alamat: <?php echo htmlspecialchars($order['address']); ?></p>
        <p>Telepon: <?php echo htmlspecialchars($order['phone']); ?></p>
        <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Beranda</a>
    </div>
</body>

</html>