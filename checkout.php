<?php
session_start();
include 'includes/database.php';

// Pastikan ada item dalam keranjang
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// Ambil detail produk untuk ditampilkan
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

// Menghitung total harga
$total_price = array_sum(array_map(function ($item) {
    return $item['product']['price'] * $item['quantity'];
}, $cart_items));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Checkout</h1>

        <h2 class="text-xl font-semibold">Informasi Pengiriman</h2>
        <form method="POST" action="process_checkout.php">
            <input type="text" name="name" placeholder="Nama Lengkap" required class="mb-2 border-2 w-full p-2" />
            <input type="text" name="address" placeholder="Alamat" required class="mb-2 border-2 w-full p-2" />
            <input type="text" name="phone" placeholder="Nomor Telepon" required class="mb-2 border-2 w-full p-2" />
            <input type="email" name="email" placeholder="Email" required class="mb-2 border-2 w-full p-2" />

            <h2 class="text-xl font-semibold mt-5">Ringkasan Pesanan</h2>
            <div class="border p-4 mb-4">
                <?php foreach ($cart_items as $item): ?>
                    <p><?php echo htmlspecialchars($item['product']['name']); ?> - Jumlah: <?php echo $item['quantity']; ?></p>
                <?php endforeach; ?>
                <p class="font-bold">Total: Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p> <!-- Tambahkan total harga di sini -->
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Konfirmasi Pesanan</button>
        </form>
    </div>
</body>

</html>