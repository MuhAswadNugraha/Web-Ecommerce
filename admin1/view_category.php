<?php
include '../includes/database.php';
include 'includes/header.php';

$search = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

try {
    // Ambil semua kategori
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Failed to fetch categories: ' . $e->getMessage());
    $categories = [];
}
?>

<main class="flex">
    <div class="min-h-screen bg-gray-100 w-96">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="container mx-auto px-6 pt-20 w-4/5">
        <h1 class="font-bold text-4xl pb-5">Dashboard Admin</h1>
        <a href="add_product.php" class="text-blue-500"><span class="font-bold text-lg">+</span> Tambah Produk</a>

        <!-- Form Pencarian -->
        <form method="POST" class="mb-4">
            <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>" class="border p-2 rounded w-full">
            <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
        </form>

        <?php foreach ($categories as $category): ?>
            <h2 class="font-bold text-2xl mt-10"><?php echo htmlspecialchars($category['name']); ?></h2>
            <table class="min-w-full border-collapse mb-20">
                <thead>
                    <tr>
                        <th class="border">ID</th>
                        <th class="border">Nama Produk</th>
                        <th class="border">Deskripsi Produk</th>
                        <th class="border">Harga</th>
                        <th class="border">Gambar Produk</th>
                        <th class="border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil produk untuk kategori ini
                    $stmt = $pdo->prepare("
                        SELECT p.* 
                        FROM products p 
                        WHERE p.category_id = ? AND (p.name LIKE ?)
                    ");
                    $stmt->execute([$category['id'], '%' . $search . '%']);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($products) === 0): ?>
                        <tr>
                            <td colspan="6" class="border text-center">Tidak ada produk ditemukan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="text-center">
                                <td class="border"><?php echo $product['id']; ?></td>
                                <td class="border"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="border"><?php echo htmlspecialchars($product['description']); ?></td>
                                <td class="border">Rp <?php echo number_format($product['price'], 2); ?></td>
                                <td class="border">
                                    <?php if (!empty($product['image']) && file_exists('../upload/' . $product['image'])): ?>
                                        <img src="../upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-40 h-40 my-5 object-cover m-auto">
                                    <?php else: ?>
                                        <img src="../assets/image/default-product.png" alt="Default Image" class="w-16 h-16 object-cover">
                                    <?php endif; ?>
                                </td>
                                <td class="border">
                                    <div class="flex gap-2 justify-center">
                                        <a href="view_product.php?id=<?php echo $product['id']; ?>" class="bg-green-500 text-white px-2 py-1 rounded">Lihat</a>
                                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>