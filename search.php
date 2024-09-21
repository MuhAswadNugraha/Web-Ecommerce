<?php
include 'includes/header.php';
include 'includes/database.php'; // Pastikan koneksi database terhubung

// Ambil kata kunci pencarian dari query string
$search = isset($_GET['query']) ? trim($_GET['query']) : '';

// Ambil produk berdasarkan pencarian
$products = [];
if ($search) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name AS category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
        ");
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Failed to fetch products: ' . $e->getMessage());
    }
}
?>

<main>
    <div class="container mx-auto px-6 pt-24">
        <h1 class="font-bold text-4xl pb-5">Hasil Pencarian untuk: <?php echo htmlspecialchars($search); ?></h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                        <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                            <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full object-cover mb-4">
                        <?php else: ?>
                            <img src="assets/images/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                        <?php endif; ?>
                        <div class="flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="font-semibold">Kategori: <?php echo htmlspecialchars($product['category_name']); ?></p>
                            <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                        </div>
                        <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                            <i class="fas fa-shopping-cart mr-5"></i>
                            Tambah Ke Keranjang
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada produk yang ditemukan untuk pencarian ini.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>