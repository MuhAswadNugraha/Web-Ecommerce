<?php
session_start();
include 'includes/database.php';

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $notification_count = count($notifications);
} else {
    $notification_count = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARAYA HOME MART</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .notification-dropdown {
            display: none;
            /* Awalnya disembunyikan */
        }

        .notification-dropdown.show {
            display: block;
            /* Ditampilkan ketika notifikasi aktif */
        }
    </style>
</head>

<body>
    <nav class="flex gap-10 items-center py-3 bg-yellow-400 justify-around text-sm px-5 fixed w-full shadow-2xl">
        <div class="logo">
            <img src="assets/image/ARAYA1.png" alt="ARAYA Home Mart Logo" class="h-12">
        </div>
        <ul class="flex gap-7 items-center font-semibold text-lg">
            <li><a href="keluarga.php" class="menu">Ruang Keluarga<br />& Ruang Tamu</a></li>
            <li><a href="dapur.php" class="menu">Dapur</a></li>
            <li><a href="tidur.php" class="menu">Kamar Tidur</a></li>
            <li><a href="mandi.php" class="menu">Kamar Mandi</a></li>
        </ul>
        <form method="GET" action="search.php" class="">
            <div class="bg-white border-2 shadow relative rounded-xl px-2 flex items-center">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="query" id="title" class="border-white outline-none border-0 w-56 rounded-xl p-2" type="text" placeholder="Apa yang Anda cari?">
                <button type="submit" class="bg-yellow-400 hover:bg-gray-700 rounded-xl text-black hover:text-white text-xl pl-4 pr-4 ml-2">
                    <p class="font-semibold text-lg">Search</p>
                </button>
            </div>
        </form>
        <ul class="flex gap-7 font-semibold text-lg">
            <li><a href="index.php" class="menu">Beranda</a></li>
            <li><a href="" class="menu">Product Kami</a></li>
            <li><a href="" class="menu">Tentang Kami</a></li>
        </ul>
        <div class="flex gap-10 items-center">
            <a href="cart.php" class="relative menu text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if ($cart_count > 0): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-xs rounded-full flex items-center justify-center"><?php echo htmlspecialchars($cart_count); ?></span>
                <?php endif; ?>
            </a>
            <div class="relative">
                <a href="./history.php" class="relative menu text-xl hover:text-red-500" id="bellIcon">
                    <i class="fa-solid fa-bell"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-xs rounded-full flex items-center justify-center"><?php echo htmlspecialchars($notification_count); ?></span>
                    <?php endif; ?>
                </a>
                <div class="notification-dropdown absolute top-16 right-5 bg-white shadow-lg rounded-lg w-64 hidden">
                    <ul>
                        <?php foreach ($notifications as $notification): ?>
                            <li class="p-2 border-b">
                                <?php echo htmlspecialchars($notification['message']); ?>
                            </li>
                        <?php endforeach; ?>
                        <?php if ($notification_count === 0): ?>
                            <li class="p-2 text-gray-500">Tidak ada notifikasi.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <span>|</span>
            <div class="flex items-center space-x-4">
                <?php if ($is_logged_in): ?>
                    <a href="profile.php" class="flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        <i class="fa-solid fa-user mr-2"></i>Profile
                    </a>
                    <a href="logout.php" class="px-5 py-1.5 bg-red-500 text-white font-bold border-2 border-red-500 rounded-xl hover:bg-white hover:text-red-500 transition-colors duration-300">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="px-5 py-1.5 font-bold border-2 border-red-500 text-red-500 rounded-xl hover:bg-red-500 hover:text-white">Masuk</a>
                    <a href="login.php" class="px-5 py-1.5 bg-red-500 text-white font-bold border-2 border-red-500 rounded-xl hover:bg-white hover:text-red-500 transition-colors duration-300">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bellIcon = document.getElementById('bellIcon');
            const notificationDropdown = document.querySelector('.notification-dropdown');

            bellIcon.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah link default
                notificationDropdown.classList.toggle('hidden'); // Toggle visibilitas dropdown
            });

            // Tambahkan klik di luar dropdown untuk menyembunyikan
            document.addEventListener('click', function(event) {
                if (!bellIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>