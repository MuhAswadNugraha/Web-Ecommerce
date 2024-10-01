<?php
session_start();
include '../includes/database.php';

// Ambil semua pesanan
$orders_stmt = $pdo->query("SELECT o.*, u.name AS user_name FROM orders o JOIN users u ON o.user_id = u.id");
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lihat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Daftar Pesanan</h1>
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID Pesanan</th>
                    <th class="border border-gray-300 p-2">Nama Pengguna</th>
                    <th class="border border-gray-300 p-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($order['id']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="text-blue-500">Kembali ke Dashboard</a>
    </div>
</body>

</html>