<?php
session_start();
include '../includes/database.php';

$order_id = $_GET['id'];
$order_stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$order_stmt->execute([$order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE orders SET user_id = ? WHERE id = ?");
    $stmt->execute([$user_id, $order_id]);

    header("Location: view_orders.php");
    exit;
}

// Ambil semua pengguna untuk dropdown
$users_stmt = $pdo->query("SELECT * FROM users");
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5 text-center">Update Pesanan</h1>
        <form action="" method="POST" class="bg-white rounded-lg shadow-md p-6">
            <label for="user_id" class="block mb-2">Pengguna:</label>
            <select name="user_id" id="user_id" class="border p-2 w-full">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['id']); ?>" <?php if ($user['id'] == $order['user_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($user['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Update Pesanan</button>
        </form>
        <div class="mt-6 text-center">
            <a href="view_orders.php" class="text-blue-500 hover:underline">Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</body>

</html>