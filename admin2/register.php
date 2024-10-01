<?php
session_start();
include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert admin baru ke database
    $stmt = $pdo->prepare("INSERT INTO admins (username, name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $name, $email, $password]);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Register Admin</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required class="border p-2 mb-2 w-full">
            <input type="text" name="name" placeholder="Nama" required class="border p-2 mb-2 w-full">
            <input type="email" name="email" placeholder="Email" required class="border p-2 mb-2 w-full">
            <input type="password" name="password" placeholder="Password" required class="border p-2 mb-2 w-full">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Register</button>
        </form>
    </div>
</body>

</html>