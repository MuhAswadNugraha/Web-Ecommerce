<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi informasi dari formulir
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash password

    // Validasi input
    if (empty($name) || empty($email) || empty($_POST['password'])) {
        header("Location: error.php?message=Semua field harus diisi");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: error.php?message=Email tidak valid");
        exit;
    }

    try {
        // Periksa apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            header("Location: error.php?message=Email sudah terdaftar");
            exit;
        }

        // Simpan informasi pengguna ke database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        // Redirect ke halaman sukses atau login
        header("Location: register_success.php");
        exit;
    } catch (PDOException $e) {
        error_log('Failed to register user: ' . $e->getMessage());
        header("Location: error.php?message=Terjadi kesalahan saat mendaftar");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white shadow-md rounded-lg p-8 w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        <form>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-200" id="email" type="email" placeholder="Masukkan email" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring focus:ring-blue-200" id="password" type="password" placeholder="Masukkan password" required>
                <p class="text-red-500 text-xs italic">Harap masukkan password yang valid.</p>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:ring-blue-200" type="submit">
                    Masuk
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                    Lupa password?
                </a>
            </div>
        </form>
    </div>

</body>

</html>