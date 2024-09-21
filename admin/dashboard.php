<?php
session_start();
include '../includes/database.php';
include 'includes/sidebar.php';

try {
    // Update query untuk melakukan join dengan tabel categories
    $stmt = $pdo->query("
        SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Failed to fetch products: ' . $e->getMessage());
    $products = [];
}
?>

<div class="container mx-auto px-6 pt-20 w-4/5">
    <h1 class="font-bold text-4xl pb-5">Dashboard Admin</h1>
    <table class="min-w-full border-collapse">
        <thead>
            <tr>
                <th class="border">ID</th>
                <th class="border">Nama Produk</th>
                <th class="border">Deskripsi Produk</th>
                <th class="border">Kategori Produk</th>
                <th class="border">Harga</th>
                <th class="border">Gambar Produk</th>
                <th class="border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="text-center">
                    <td class="border"><?php echo $product['id']; ?></td>
                    <td class="border"><?php echo htmlspecialchars($product['name']); ?></td>
                    <td class="border"><?php echo htmlspecialchars($product['description']); ?></td>
                    <td class="border"><?php echo htmlspecialchars($product['category_name']); ?></td> <!-- Ganti category_id dengan category_name -->
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
        </tbody>
    </table>
    <a href="add_product.php" class="text-blue-500">Add New Product</a>
</div>

</body>

</html>