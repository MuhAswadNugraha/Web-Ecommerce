<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Simpan pesan ke database
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $message]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Hubungi Kami</h1>
        <form method="POST" action="">
            <p>Nama Lengkap</p>
            <input type="text" name="name" class="mb-2 px-2 border-2" required />
            <p>Email</p>
            <input type="email" name="email" class="mb-2 px-2 border-2" required />
            <p>Nomor Handphone</p>
            <input type="text" name="phone" class="mb-2 px-2 border-2" required />
            <p>Pesan</p>
            <textarea name="message" class="mb-2 px-2 border-2" required></textarea>
            <div class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 rounded-md transition text-center">
                <button type="submit">Kirim</button>
            </div>
        </form>
    </div>
</body>

</html>