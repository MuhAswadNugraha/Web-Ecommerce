<?php
session_start(); // Start session
include 'includes/database.php';
include 'includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'reduce' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        if ($_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Fetch cart items
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $product) {
        $cart_items[] = [
            'product' => $product,
            'quantity' => $_SESSION['cart'][$product['id']]
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-24">
        <h1 class="font-bold text-4xl pb-5">Keranjang Belanja</h1>

        <?php if (empty($cart_items)): ?>
            <p>Keranjang Anda kosong.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($cart_items as $item): ?>
                    <div class="border-2 border-black rounded-lg p-4">
                        <img src="upload/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="w-auto h-auto object-cover mb-4">
                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                        <p class="font-bold">Harga: Rp <?php echo number_format($item['product']['price'] * $item['quantity'], 2); ?></p>
                        <p>Jumlah: <?php echo $item['quantity']; ?></p>
                        <div class="flex gap-2 mt-2">
                            <a href="cart.php?action=reduce&id=<?php echo $item['product']['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded">Kurangi</a>
                            <a href="cart.php?action=remove&id=<?php echo $item['product']['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="checkout.php" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">Checkout</a>
        <?php endif; ?>
    </div>
</body>

</html>