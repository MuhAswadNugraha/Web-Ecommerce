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
        }

        .notification-dropdown.show {
            display: block;
        }

        /* Add transition for mobile menu */
        #mobile-menu {
            padding-top: 70px;
            transition: max-height 0.3s ease-out;
            overflow: hidden;
        }

        /* Initially set max-height to 0 */
        #mobile-menu.collapsed {
            max-height: 0;
        }

        #mobile-menu.expanded {
            max-height: 500px;
            /* Arbitrarily large height */
        }
    </style>
</head>

<body>
    <nav class="flex flex-wrap items-center justify-between py-3 bg-yellow-400 fixed w-full shadow-2xl px-5">
        <div class="logo">
            <img src="assets/image/ARAYA1.png" alt="ARAYA Home Mart Logo" class="h-12">
        </div>
        <div class="block lg:hidden">
            <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-7 lg:items-center font-semibold text-lg w-full lg:w-auto">
            <ul class="flex flex-col lg:flex-row gap-7 items-center scroll-smoot">
                <li><a href="keluarga.php" class="menu">Ruang Keluarga<br />& Ruang Tamu</a></li>
                <li><a href="dapur.php" class="menu">Dapur</a></li>
                <li><a href="tidur.php" class="menu">Kamar Tidur</a></li>
                <li><a href="mandi.php" class="menu">Kamar Mandi</a></li>
            </ul>
            <form method="GET" action="search.php" class="mt-4 lg:mt-0">
                <div class="bg-white border-2 shadow relative rounded-xl px-2 flex items-center">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input name="query" id="title" class="border-white outline-none border-0 w-56 rounded-xl p-2" type="text" placeholder="Apa yang Anda cari?">
                    <button type="submit" class="bg-yellow-400 hover:bg-gray-700 rounded-xl text-black hover:text-white text-xl pl-4 pr-4 ml-2">
                        <p class="font-semibold text-lg">Search</p>
                    </button>
                </div>
            </form>
            <ul class="flex flex-col lg:flex-row gap-7 items-center">
                <li><a href="index.php" class="menu">Beranda</a></li>
                <li><a href="#produk" class="menu">Product Kami</a></li>
                <li><a href="#awalan" class="menu">Tentang Kami</a></li>
            </ul>
            <div class="flex gap-10 items-center">
                <a href="cart.php" class="relative menu text-xl">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-xs rounded-full flex items-center justify-center"><?php echo htmlspecialchars($cart_count); ?></span>
                    <?php endif; ?>
                </a>
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
        </div>
    </nav>

    <div id="mobile-menu" class="collapsed lg:hidden bg-yellow-400">
        <div class="flex flex-col p-5">
            <ul class="flex flex-col gap-4">
                <li><a href="keluarga.php" class="menu">Ruang Keluarga<br />& Ruang Tamu</a></li>
                <li><a href="dapur.php" class="menu">Dapur</a></li>
                <li><a href="tidur.php" class="menu">Kamar Tidur</a></li>
                <li><a href="mandi.php" class="menu">Kamar Mandi</a></li>
                <li><a href="index.php" class="menu">Beranda</a></li>
                <li><a href="" class="menu">Product Kami</a></li>
                <li><a href="#awalan" class="menu">Tentang Kami</a></li>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('collapsed');
            mobileMenu.classList.toggle('expanded');
        });
    </script>
</body>

</html>