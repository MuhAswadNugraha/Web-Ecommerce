<?php include 'includes/header.php';

try {
    // Ambil semua produk
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tamuProducts = array_filter($products, function ($product) {
        return $product['category_id'] == '2'; // Sesuaikan dengan ID kategori Dapur
    });
} catch (PDOException $e) {
    error_log('Failed to fetch products: ' . $e->getMessage());
    $tamuProducts = [];
    $dapurProducts = [];
}
?>

<main>
    <div class="container mx-auto px-6 pt-20">
        <img src="assets/image/bg-1.png" class="w-full pt-10" alt="Background Image">
        <div class="flex mt-2">
            <a href="claim_voucher.php"><img src="assets/image/bg-2.png" style="width: 900px;" alt=""></a>
            <a href="claim_voucher.php"><img src="assets/image/1.png" style="width: 185px;" alt=""></a>
            <a href="claim_voucher.php"><img src="assets/image/2.png" style="width: 265px;" alt=""></a>
            <a href="claim_voucher.php"><img src="assets/image/3.png" style="width: 220px;" alt=""></a>
        </div>

        <h1 class="font-bold text-4xl pb-5 pt-4">Ruang Keluarga & Ruang Tamu</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($tamuProducts as $product): ?>
                <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                    <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-auto object-cover mb-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div class="flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                        <i class="fas fa-shopping-cart mr-5"></i>
                        Tambah Ke Keranjang
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>


<?php include 'includes/footer.php'; ?>