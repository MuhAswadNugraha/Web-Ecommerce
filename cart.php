<?php
include 'includes/database.php'; // Include file untuk koneksi ke database
include 'includes/header.php'; // Include header jika ada
session_start();
// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika user belum login
    header("Location: login.php");
    exit;
}

// Inisialisasi session cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fungsi untuk menambah produk ke keranjang
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++; // Jika produk sudah ada, tambahkan jumlahnya
    } else {
        $_SESSION['cart'][$product_id] = 1; // Jika belum ada, tambahkan ke keranjang
    }
}

// Fungsi untuk mengurangi jumlah produk dalam keranjang
if (isset($_GET['action']) && $_GET['action'] == 'reduce' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        if ($_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--; // Kurangi jumlah produk
        } else {
            unset($_SESSION['cart'][$product_id]); // Hapus dari keranjang jika jumlahnya 0
        }
    }
}

// Fungsi untuk menghapus produk dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Hapus produk dari keranjang
    }
}

// Ambil produk dari database berdasarkan ID di keranjang
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?')); // Placeholder untuk query
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart'])); // Ambil produk berdasarkan ID dari session cart
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch semua produk

    // Gabungkan produk dengan jumlah yang ada di keranjang
    foreach ($products as $product) {
        $cart_items[] = [
            'product' => $product,
            'quantity' => $_SESSION['cart'][$product['id']] // Ambil jumlah dari session cart
        ];
    }
}

// Hitung total harga
$total_price = array_sum(array_map(function ($item) {
    return $item['product']['price'] * $item['quantity'];
}, $cart_items));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-24">
        <h1 class="font-bold text-4xl pb-5">Keranjang Belanja</h1>

        <?php if (empty($cart_items)): ?>
            <p>Keranjang Anda kosong.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($cart_items as $item): ?>
                    <div class="border-2 border-black rounded-lg p-4">
                        <img src="upload/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="w-96 h-96 m-auto object-cover mb-4">
                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                        <p class="font-bold">Harga: Rp <?php echo number_format($item['product']['price'] * $item['quantity'], 2); ?></p>
                        <p>Jumlah: <?php echo $item['quantity']; ?></p>
                        <div class="flex gap-2 mt-2">
                            <a href="cart.php?action=add&id=<?php echo $item['product']['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded">Tambah</a>
                            <a href="cart.php?action=reduce&id=<?php echo $item['product']['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded">Kurangi</a>
                            <a href="cart.php?action=remove&id=<?php echo $item['product']['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>

            <!-- Tampilkan total harga dan link ke checkout -->
            <div class="mt-4">
                <p class="font-bold text-xl">Total: Rp <?php echo number_format($total_price, 2); ?></p>
                <a href="payment.php" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>