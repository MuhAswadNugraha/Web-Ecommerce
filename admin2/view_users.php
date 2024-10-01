<?php
session_start();
include '../includes/database.php';

// Ambil semua pengguna
$users_stmt = $pdo->query("SELECT * FROM users");
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lihat Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Daftar Pengguna</h1>
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID Pengguna</th>
                    <th class="border border-gray-300 p-2">Nama</th>
                    <th class="border border-gray-300 p-2">Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="text-blue-500">Kembali ke Dashboard</a>
    </div>
</body>

</html>