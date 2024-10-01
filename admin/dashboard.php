<?php
include '../includes/database.php';
include 'includes/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: logina.php'); // Redirect to login page
    exit;
}

// Get total number of products
$product_stmt = $pdo->query("SELECT COUNT(*) AS total FROM products");
$product_count = $product_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get total number of orders
$order_stmt = $pdo->query("SELECT COUNT(*) AS total FROM orders");
$order_count = $order_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get total number of userss (assuming there's a userss table)
$users_stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
$users_count = $users_stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<div class="flex">
    <div class="min-h-screen bg-gray-100 w-96">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="container mx-auto px-6 pt-20 w-4/5">
        <div class="flex justify-around">
            <div class="bg-blue-300 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/produk.png" alt="">
                    <p>Total Product</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold"><?php echo $product_count; ?></p>
                <hr class="border-t-2 border-black">
                <a href="view_product.php">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
            <div class="bg-green-200 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/orde.png" alt="">
                    <p>Total Orders</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold"><?php echo $order_count; ?></p>
                <hr class="border-t-2 border-black">
                <a href="view_orders.php">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
            <div class="bg-blue-300 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/users.png" alt="">
                    <p>Total User</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold"><?php echo $users_count; ?></p>
                <hr class="border-t-2 border-black">
                <a href="view_users.php">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
        </div>
    </div>
</div>
</body>

</html>