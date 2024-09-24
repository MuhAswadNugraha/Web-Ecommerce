<?php
session_start();
include '../includes/database.php';

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'logina') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['role'] = 'admin'; // Set the role

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Email atau password salah.";
            }
        } else {
            $error = "Email dan password harus diisi.";
        }
    } elseif ($_POST['action'] === 'register') {
        // Registration logic
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.7.1/cdn.min.js" defer></script>
    <style>
        /* Custom animations */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div x-data="{ showLogin: true }" class="w-full max-w-md mx-auto p-6 bg-white rounded-lg shadow-xl fade-in">
        <!-- Toggle between login and registration -->
        <div class="flex justify-center mb-6">
            <button @click="showLogin = true" class="px-4 py-2 w-1/2 text-center transition bg-gray-300 rounded-tl-lg"
                :class="{ 'bg-blue-500 text-white': showLogin }">Login</button>
            <button @click="showLogin = false" class="px-4 py-2 w-1/2 text-center transition bg-gray-300 rounded-tr-lg"
                :class="{ 'bg-blue-500 text-white': !showLogin }">Registrasi</button>
        </div>

        <!-- Error message display -->
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-2 bg-red-100 border border-red-400 text-red-700 rounded-lg fade-in">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <div x-show="showLogin" x-cloak class="fade-in">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>
            <form method="POST" action="logina.php" class="space-y-4">
                <input type="hidden" name="action" value="logina">
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">Login</button>
            </form>
        </div>

        <!-- Registration form -->
        <div x-show="!showLogin" x-cloak class="fade-in">
            <h2 class="text-2xl font-bold mb-4 text-center">Registrasi</h2>
            <form method="POST" action="logina.php" class="space-y-4">
                <input type="hidden" name="action" value="register">
                <div>
                    <label for="name" class="block text-gray-700">Nama Lengkap:</label>
                    <input type="text" name="name" id="name" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label for="confirm_password" class="block text-gray-700">Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="border-2 border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition">Daftar</button>
            </form>
        </div>
    </div>
</body>

</html>