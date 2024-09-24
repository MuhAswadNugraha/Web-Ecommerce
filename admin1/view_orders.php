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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5 text-center">Daftar Pesanan</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-4 text-left">ID Pesanan</th>
                        <th class="border border-gray-300 p-4 text-left">Nama Pengguna</th>
                        <th class="border border-gray-300 p-4 text-left">Tanggal</th>
                        <th class="border border-gray-300 p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-100 transition duration-150">
                            <td class="border border-gray-300 p-4"><?php echo htmlspecialchars($order['id']); ?></td>
                            <td class="border border-gray-300 p-4"><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td class="border border-gray-300 p-4"><?php echo htmlspecialchars($order['created_at']); ?></td>
                            <td class="border border-gray-300 p-4">
                                <a href="view_order_detail.php?id=<?php echo $order['id']; ?>" class="text-blue-500 hover:underline">Lihat Detail</a>
                                <a href="update_orders.php?id=<?php echo $order['id']; ?>" class="text-yellow-500 hover:underline ml-2">Update</a>
                                <a href="delete_orders.php?id=<?php echo $order['id']; ?>" class="text-red-500 hover:underline ml-2" onclick="return confirm('Anda yakin ingin menghapus pesanan ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center">
            <a href="dashboard.php" class="text-blue-500 hover:underline">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>