<?php
include '../includes/database.php';
include 'includes/header.php';

// Check if product ID is set
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$product_id = intval($_GET['id']);

// Fetch product data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: dashboard.php');
    exit;
}

// Fetch categories for the dropdown
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);

    // Validate input
    if (empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
        echo "Semua kolom harus diisi dengan benar.";
    } else {
        // Update product
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $category_id, $product_id]);

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../upload/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);

            // Check if the file is uploaded
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update image name in database
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
            <select name="category_id" class="border p-2 mb-2 w-full">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="image" class="border p-2 mb-2 w-full" />
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Produk</button>
        </form>
    </div>
</main>