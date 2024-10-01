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
$stmt = $pdo->prepare("SELECT fullname, dob, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data alamat dari tabel addresses
$stmt = $pdo->prepare("SELECT address_line, city, postal_code, country FROM addresses WHERE user_id = ?");
$stmt->execute([$user_id]);
$address = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah profil lengkap
if (
    empty($user['fullname']) || empty($user['dob']) || empty($user['phone']) ||
    empty($address['address_line']) || empty($address['city']) || empty($address['postal_code']) || empty($address['country'])
) {
    header("Location: profile.php?incomplete_profile=1");
    exit();
}

// Pastikan keranjang tidak kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Tidak ada pesanan yang ditemukan. Silakan kembali ke keranjang.");
}

// Ambil detail produk dari keranjang
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        if (isset($_SESSION['cart'][$product['id']])) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total_price += $subtotal;

            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

// Cek jika voucher ada di POST
$voucher_code = isset($_POST['voucher_code']) ? trim($_POST['voucher_code']) : '';
$discount = 0;

if ($voucher_code) {
    $stmt = $pdo->prepare("SELECT discount FROM vouchers WHERE code = ? AND expiry_date >= NOW()");
    $stmt->execute([$voucher_code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($voucher) {
        $discount = $voucher['discount'];
        $total_price -= ($total_price * ($discount / 100));
        $_SESSION['voucher_code'] = $voucher_code; // Simpan kode voucher ke session
    } else {
        echo "<script>alert('Voucher tidak valid atau sudah expired.');</script>";
    }
}

// Simpan total akhir setelah diskon ke variabel
$final_total = $total_price;

// Proses pembayaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];

    // Buat pesanan baru
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, created_at) VALUES (?, 'pending', NOW())");
    if (!$stmt->execute([$user_id])) {
        die("Error creating order: " . implode(", ", $stmt->errorInfo()));
    }
    $order_id = $pdo->lastInsertId();

    // Simpan informasi pembayaran dengan total harga yang sudah didiskon
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    if (!$stmt->execute([$order_id, $final_total, $payment_method])) {
        die("Error saving payment: " . implode(", ", $stmt->errorInfo()));
    }

    // Kosongkan keranjang dan kode voucher
    unset($_SESSION['cart']);
    unset($_SESSION['voucher_code']); // Kosongkan kode voucher setelah digunakan

    // Set status sukses untuk pop-up
    $payment_success = true;
} else {
    $payment_success = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1000;
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .popup.active {
            display: block;
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        .icon {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="bg-yellow-50">
    <div class="overlay <?php echo $payment_success ? 'active' : ''; ?>"></div>
    <div class="popup <?php echo $payment_success ? 'active' : ''; ?>">
        <div class="text-center">
            <i class="fas fa-check-circle icon"></i>
            <h2 class="text-lg font-bold">Pembayaran Berhasil!</h2>
            <p>Terima kasih telah berbelanja!</p>
            <button onclick="redirectHome()" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md mt-4">Tutup</button>
        </div>
    </div>

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
                    <tr>
                        <td class="border px-4 py-2 text-center">
                            <img src="upload/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="w-20 h-20 m-auto object-cover">
                        </td>
                        <td class="border px-4 py-2 text-center"><?php echo htmlspecialchars($item['product']['name']); ?></td>
                        <td class="border px-4 py-2 text-center">Rp <?php echo number_format($item['product']['price'], 2, ',', '.'); ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $item['quantity']; ?></td>
                        <td class="border px-4 py-2 text-center">Rp <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-6 text-right">
            <span class="font-bold text-lg">Total Keseluruhan:</span>
            <?php if ($discount > 0): ?>
                <p class="font-bold text-lg line-through">Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p>
                <p class="font-bold text-xl text-red-500">Diskon: <?php echo htmlspecialchars($discount); ?>%</p>
            <?php endif; ?>
            <p class="font-bold text-xl">Rp <?php echo number_format($final_total, 2, ',', '.'); ?></p>
        </div>
    </div>

    <div class="bg-white rounded justify-center w-3/4 m-auto mt-20 px-7 py-5">
        <form action="" method="post">
            <h2 class="font-bold mb-4">Masukkan Voucher (jika ada)</h2>
            <input type="text" name="voucher_code" placeholder="Masukkan kode voucher" class="w-full px-4 py-2 border border-gray-300 rounded-md mb-3">
            <button type="submit" class="bg-green-500 text-white font-bold py-2 rounded-md hover:bg-green-600">Terapkan Voucher</button>
        </form>
    </div>

    <div class="bg-white rounded justify-center w-3/4 m-auto mt-20 px-7 py-5">
        <form action="payment.php" method="post">
            <h2 class="font-bold mb-4">Metode Pembayaran</h2>
            <div class="mb-3">
                <label for="payment_method" class="block text-lg font-medium">Metode Pembayaran</label>
                <select id="payment_method" name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="cash_on_delivery">Bayar di Tempat</option>
                </select>
            </div>
            <a href="index.php" class="block w-full text-center bg-red-500 text-white font-bold py-2 rounded-md hover:bg-red-600 mb-3">Batalkan Pembayaran</a>
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded-md hover:bg-blue-600">Konfirmasi Pembayaran</button>
        </form>
    </div>

    <script>
        function redirectHome() {
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 5000);
        }

        <?php if ($payment_success): ?>
            redirectHome();
        <?php endif; ?>
    </script>
</body>

</html>