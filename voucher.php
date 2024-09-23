<?php
session_start();
include 'includes/database.php';

// Fetch active vouchers
$stmt = $pdo->prepare("SELECT * FROM vouchers WHERE is_active = 1 AND valid_until > NOW()");
$stmt->execute();
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle voucher redemption
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['voucher_code'];
    $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = ? AND is_active = 1 AND valid_until > NOW()");
    $stmt->execute([$code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($voucher) {
        $_SESSION['discount'] = $voucher['discount'];
        echo "<p class='text-green-500'>Voucher berhasil diterapkan! Diskon: Rp " . number_format($voucher['discount'], 2) . "</p>";
    } else {
        echo "<p class='text-red-500'>Voucher tidak valid atau sudah kadaluarsa.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Diskon</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Voucher Diskon</h1>

        <form method="POST" class="mb-4">
            <input type="text" name="voucher_code" placeholder="Masukkan Kode Voucher" required class="border-2 p-2" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Terapkan Voucher</button>
        </form>

        <h2 class="text-xl font-semibold">Voucher Tersedia:</h2>
        <ul class="list-disc pl-5">
            <?php foreach ($vouchers as $voucher): ?>
                <li><?php echo htmlspecialchars($voucher['code']) . " - Diskon: Rp " . number_format($voucher['discount'], 2) . " (Berakhir: " . date('d-m-Y', strtotime($voucher['valid_until'])) . ")"; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>