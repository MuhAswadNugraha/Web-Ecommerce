<?php
session_start();
$is_logged_in = isset($_SESSION['admin_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <nav class="flex items-center bg-yellow-300 justify-around">
        <div class="flex gap-5 items-center py-2">
            <img src="../assets/image/ARAYA1.png" alt="Araya" class="w-40">
            <p class="text-4xl font-bold mr-1">|</p>
            <h1 class="font-bold text-4xl">Dashboard</h1>
        </div>
        <div class="flex items-center space-x-4">
            <?php if ($is_logged_in): ?>
                <a href="../profile.php" class="flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    <i class="fa-solid fa-user mr-2"></i>Profile
                </a>
                <a href="logout.php" class="px-5 py-1.5 bg-red-500 text-white font-bold border-2 border-red-500 rounded-xl hover:bg-white hover:text-red-500 transition-colors duration-300">
                    Logout
                </a>
            <?php else: ?>
                <a href="logina.php" class="px-5 py-1.5 font-bold border-2 border-red-500 text-red-500 rounded-xl hover:bg-red-500 hover:text-white">Masuk</a>
                <a href="logina.php" class="px-5 py-1.5 bg-red-500 text-white font-bold border-2 border-red-500 rounded-xl hover:bg-white hover:text-red-500 transition-colors duration-300">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>