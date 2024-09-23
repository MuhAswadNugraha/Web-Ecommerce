<?php
session_start();
include '../includes/database.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$product_id = intval($_GET['id']);

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: dashboard.php');
    exit;
}
?>

<main>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Detail Produk</h1>
        <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
            <img src="../upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full object-cover mb-4">
            <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded">Edit Produk</a>
            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Hapus Produk</a>
        </div>
        <a href="dashboard.php" class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">Kembali ke Dashboard</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>