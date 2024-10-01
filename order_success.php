<?php
session_start();
include 'includes/database.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil ID pesanan dari URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan dari tabel orders
$stmt = $pdo->prepare("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Pesanan tidak ditemukan. Silakan kembali ke keranjang.");
}

// Ambil detail produk yang dipesan
$stmt = $pdo->prepare("SELECT p.*, oi.quantity, oi.subtotal FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    die("Tidak ada item dalam pesanan ini.");
}

// Total harga sebelum diskon
$total_price = array_sum(array_column($cart_items, 'subtotal'));

// Inisialisasi diskon
$discount = 0;

// Cek jika voucher ada di session
$voucher_code = isset($_SESSION['voucher_code']) ? $_SESSION['voucher_code'] : '';

if ($voucher_code) {
    $stmt = $pdo->prepare("SELECT discount FROM vouchers WHERE code = ? AND expiry_date >= NOW()");
    $stmt->execute([$voucher_code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($voucher) {
        $discount = $voucher['discount'];
        // Hitung total setelah diskon
        $discount_amount = $total_price * ($discount / 100);
        $final_total = $total_price - $discount_amount;
    } else {
        $final_total = $total_price; // Jika voucher tidak valid, tidak ada diskon
    }
} else {
    $final_total = $total_price; // Jika tidak ada voucher, total tetap
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 pt-20">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="font-bold text-4xl text-center text-green-600 mb-5">Pesanan Berhasil!</h1>
            <p class="text-lg text-center">Terima kasih, <span class="font-bold"><?php echo htmlspecialchars($order['fullname']); ?></span>. Pesanan Anda telah berhasil diproses.</p>
            <p class="text-lg text-center">ID Pesanan: <span class="font-bold"><?php echo htmlspecialchars($order_id); ?></span></p>

            <h2 class="font-bold text-2xl mt-6 mb-4 text-center">Produk yang Dipesan</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Gambar</th>
                            <th class="border px-4 py-2">Nama Produk</th>
                            <th class="border px-4 py-2">Harga Satuan</th>
                            <th class="border px-4 py-2">Jumlah</th>
                            <th class="border px-4 py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td class="border px-4 py-2 text-center">
                                    <img src="upload/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-20 h-20 m-auto object-cover">
                                </td>
                                <td class="border px-4 py-2 text-center"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="border px-4 py-2 text-center">Rp <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                                <td class="border px-4 py-2 text-center"><?php echo $item['quantity']; ?></td>
                                <td class="border px-4 py-2 text-center">Rp <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <p class="text-lg">Total Sebelum Diskon:</p>
                <p class="text-xl font-bold text-green-600">Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p>

                <?php if ($discount > 0): ?>
                    <p class="text-lg">Diskon (<?php echo htmlspecialchars($discount); ?>%): -Rp <?php echo number_format($total_price * ($discount / 100), 2, ',', '.'); ?></p>
                <?php endif; ?>

                <p class="text-lg font-bold">Total Pembayaran:</p>
                <p class="text-xl font-bold text-green-600">Rp <?php echo number_format($final_total, 2, ',', '.'); ?></p>
            </div>

            <div class="mt-8 text-center">
                <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                <p class="text-lg">Pesanan Anda telah diterima dan sedang diproses.</p>
                <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>

</html>