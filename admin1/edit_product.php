<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    // Validasi input
    if (empty($name) || empty($description) || $price <= 0) {
        echo "Semua kolom harus diisi dengan benar.";
    } else {
        // Update produk
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $product_id]);

        // Jika ada gambar baru, proses upload gambar
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../upload/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);

            // Cek apakah file gambar dapat di upload
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update nama file gambar di database
                $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                $stmt->execute([$_FILES["image"]["name"], $product_id]);
            } else {
                echo "Gagal mengupload gambar.";
            }
        }

        header('Location: dashboard.php?message=Produk berhasil diperbarui');
        exit;
    }
}
?>

<main>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Edit Produk</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required class="border p-2 mb-2 w-full" />
            <textarea name="description" required class="border p-2 mb-2 w-full"><?php echo htmlspecialchars($product['description']); ?></textarea>
            <input type="number" name="price" value="<?php echo $product['price']; ?>" required class="border p-2 mb-2 w-full" />
            <input type="file" name="image" class="border p-2 mb-2 w-full" />
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Produk</button>
        </form>
    </div>
</main>