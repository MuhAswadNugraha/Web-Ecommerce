<?php
session_start();

// Pastikan total harga dan item keranjang sudah ada di session
if (!isset($_SESSION['total_price']) || !isset($_SESSION['cart'])) {
    die("Tidak ada pesanan yang ditemukan. Silakan kembali ke keranjang.");
}

// Ambil detail produk dari keranjang
include 'includes/database.php';
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        if (isset($_SESSION['cart'][$product['id']])) {
            $cart_items[] = [
                'product' => $product,
                'quantity' => $_SESSION['cart'][$product['id']]
            ];
        }
    }
}

$total_price = $_SESSION['total_price']; // Total harga diambil dari session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="my-4">Ringkasan Pesanan</h2>
        <div class="border p-4 mb-4">
            <?php foreach ($cart_items as $item): ?>
                <p><?php echo htmlspecialchars($item['product']['name']); ?> - Jumlah: <?php echo $item['quantity']; ?></p>
            <?php endforeach; ?>
 <p class="font-bold">Total: Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p> <!-- Tambahkan total harga di sini -->        </div>

        <form action="process_payment.php" method="post">
            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select id="payment_method" name="payment_method" class="form-select" required>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>