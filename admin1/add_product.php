<?php
session_start();
include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../upload/" . $image);

    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $stock, $category_id, $image]);
    header('Location: view_product.php');
}

$categories_stmt = $pdo->query("SELECT * FROM categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Add Product</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required class="border p-2 mb-2 w-full">
            <textarea name="description" placeholder="Description" class="border p-2 mb-2 w-full"></textarea>
            <input type="number" name="price" placeholder="Price" required class="border p-2 mb-2 w-full">
            <input type="number" name="stock" placeholder="Stock" required class="border p-2 mb-2 w-full">
            <select name="category_id" class="border p-2 mb-2 w-full">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="image" class="border p-2 mb-2 w-full">
            <button type="submit" class="bg-blue-500 text-white p-2">Add Product</button>
        </form>
        <a href="view_product.php" class="text-blue-500">Back to Products</a>
    </div>
</body>

</html>