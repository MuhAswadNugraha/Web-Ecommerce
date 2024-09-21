// payment.php
<?php
session_start();
include 'includes/database.php';

// Pastikan pesanan ada dan sudah dibuat sebelumnya
if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil informasi pesanan berdasarkan ID
$order_id = $_SESSION['order_id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Pembayaran Pesanan</h1>
        <p>Pesanan Anda ID: <?php echo htmlspecialchars($order['id']); ?></p>

        <!-- Form pembayaran -->
        <form method="POST" action="process_payment.php">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>" />
            <input type="text" name="card_number" placeholder="Nomor Kartu" required class="mb-2 border-2 w-full p-2" />
            <input type="text" name="expiry_date" placeholder="Tanggal Kadaluarsa (MM/YY)" required class="mb-2 border-2 w-full p-2" />
            <input type="text" name="cvv" placeholder="CVV" required class="mb-2 border-2 w-full p-2" />
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Bayar</button>
        </form>
    </div>
</body>

</html>