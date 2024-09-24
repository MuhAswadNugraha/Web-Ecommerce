<?php
session_start();
include 'includes/database.php';
include 'header2.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari tabel users
$stmt = $pdo->prepare("SELECT fullname, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data alamat dari tabel addresses
$stmt = $pdo->prepare("SELECT address_line, city, postal_code, country FROM addresses WHERE user_id = ?");
$stmt->execute([$user_id]);
$address = $stmt->fetch(PDO::FETCH_ASSOC);

// Pastikan total harga dan item keranjang sudah ada di session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Tidak ada pesanan yang ditemukan. Silakan kembali ke keranjang.");
}

// Ambil detail produk dari keranjang
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

// Inisialisasi total keseluruhan
$total_price = 0;
?>

<body class="bg-yellow-50">
    <div class="bg-white rounded justify-center w-3/4 m-auto mt-20 px-7 py-5 text-xl">
        <i class="fa-solid fa-location-dot text-yellow-500"></i><span class="font-bold"> Alamat Pengirim</span>
        <br>
        <div class="mt-4 flex items-center">
            <p class="font-bold p-5 rounded-md pr-20">
                <?php echo htmlspecialchars($user['fullname']); ?> <br> <?php echo htmlspecialchars($user['phone']); ?>
            </p>
            <p class="font-bold p-5 rounded-md">
                Provinsi <?php echo htmlspecialchars($address['country']); ?>, Kota <?php echo htmlspecialchars($address['city']); ?>, Kode Pos : <?php echo htmlspecialchars($address['postal_code']); ?>, <?php echo htmlspecialchars($address['address_line']); ?>
            </p>
        </div>
    </div>

    <div class="bg-white rounded justify-center w-3/4 m-auto mt-20 px-7 py-5">
        <h2 class="font-bold text-xl mb-4">Produk Dipesan</h2>
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
                    <?php
                    // Hitung subtotal untuk setiap item
                    $subtotal = $item['product']['price'] * $item['quantity'];
                    // Tambahkan subtotal ke total keseluruhan
                    $total_price += $subtotal;
                    ?>
                    <tr>
                        <td class="border px-4 py-2 text-center">
                            <img src="upload/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="w-20 h-20 m-auto object-cover">
                        </td>
                        <td class="border px-4 py-2 text-center"><?php echo htmlspecialchars($item['product']['name']); ?></td>
                        <td class="border px-4 py-2 text-center">Rp <?php echo number_format($item['product']['price'], 2, ',', '.'); ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $item['quantity']; ?></td>
                        <td class="border px-4 py-2 text-center">Rp <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-6 text-right">
            <span class="font-bold text-lg">Total Keseluruhan:</span>
            <p class="font-bold text-xl">Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p>
        </div>
    </div>

    <div class="bg-white rounded justify-center w-3/4 m-auto mt-20 px-7 py-5">
        <form action="process_payment.php" method="post">
            <h2 class="font-bold mb-4">Metode Pembayaran</h2>
            <div class="mb-3">
                <label for="payment_method" class="block text-lg font-medium">Payment Method</label>
                <select id="payment_method" name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            <input type="hidden" name="cart_items" value="<?php echo htmlspecialchars(json_encode($_SESSION['cart'])); ?>">

            <a href="index.php" class="block w-full text-center bg-red-500 text-white font-bold py-2 rounded-md hover:bg-red-600 mb-3">Batalkan Pembayaran</a>
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded-md hover:bg-blue-600">Konfirmasi Pembayaran</button>
        </form>
    </div>
</body>