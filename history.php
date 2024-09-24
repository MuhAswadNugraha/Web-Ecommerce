<?php
session_start();
include 'includes/database.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if (!$is_logged_in) {
    header("Location: login.php");
    exit;
}

// Fitur pencarian
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Ambil riwayat pembelian beserta detail produk
$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.created_at, o.status, oi.quantity, p.name AS product_name, p.image
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ? AND (o.id LIKE ? OR o.created_at LIKE ?)
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id, "%$search_query%", "%$search_query%"]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Riwayat Pembelian</h1>

        <!-- Form Pencarian -->
        <form method="GET" class="mb-6">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="border p-2 rounded w-1/2" placeholder="Cari ID Pesanan atau Tanggal">
            <button type="submit" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">Cari</button>
        </form>

        <?php if (count($orders) > 0): ?>
            <ul class="space-y-6">
                <?php
                $current_order_id = null;
                foreach ($orders as $order):
                    if ($current_order_id !== $order['order_id']):
                        if ($current_order_id !== null): ?>
            </ul>
        <?php endif; ?>
        <li class="border rounded-lg p-4 shadow-md bg-white">
            <p class="font-semibold">ID Pesanan: <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p>Tanggal: <?php echo htmlspecialchars($order['created_at']); ?></p>
            <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>

            <h2 class="mt-4 font-semibold">Produk yang Dipesan:</h2>
            <ul class="mt-2 space-y-2">
                <?php $current_order_id = $order['order_id']; ?>
            <?php endif; ?>
            <li class="flex items-center justify-between">
                <div class="flex items-center">
                    <?php if (!empty($order['image']) && file_exists('upload/' . $order['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="h-16 w-16 object-cover mr-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div>
                        <p class="font-medium"><?php echo htmlspecialchars($order['product_name']); ?></p>
                        <p>Jumlah: <?php echo htmlspecialchars($order['quantity']); ?></p>
                    </div>
                </div>
                <!-- Tombol Berikan Rating -->
                <div class="mt-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 rating-btn" data-order-id="<?php echo htmlspecialchars($order['order_id']); ?>">Berikan Rating</button>
                    <div class="rating-section hidden mt-4" id="rating-<?php echo htmlspecialchars($order['order_id']); ?>">
                        <h2 class="font-semibold">Berikan Rating:</h2>
                        <div class="flex items-center mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="cursor-pointer text-xl star" data-rating="<?php echo $i; ?>">
                                    <i class="fa-solid fa-star"></i>
                                </span>
                            <?php endfor; ?>
                        </div>
                        <textarea class="border w-full p-2 rounded" rows="3" placeholder="Tulis komentar..."></textarea>
                        <button class="mt-2 bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">Kirim</button>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
            </ul>
        </li>
    <?php else: ?>
        <p class="text-gray-500">Tidak ada riwayat pembelian.</p>
    <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll('.rating-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const ratingSection = document.getElementById('rating-' + orderId);
                ratingSection.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.querySelectorAll('.star').forEach(s => {
                    s.style.color = s.getAttribute('data-rating') <= rating ? 'gold' : 'gray';
                });
            });
        });
    </script>
</body>

</html>