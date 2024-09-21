<?php
session_start();
include '../includes/database.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan admin berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_name'] = $admin['name']; // Simpan nama admin dalam sesi
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Username atau password salah.";
        }
    } else {
        echo "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto mt-20">
        <h2 class="text-2xl font-bold mb-4">Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required class="border p-2 mb-4 w-full" />
            <input type="password" name="password" placeholder="Password" required class="border p-2 mb-4 w-full" />
            <button type="submit" class="bg-blue-500 text-white p-2 w-full">Login</button>
        </form>
    </div>
</body>

</html>