<?php
session_start();
include 'includes/database.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Jika form disubmit, update data profil di database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Update data pengguna di tabel users
    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, dob = ?, address = ?, phone = ? WHERE id = ?");
    $stmt->execute([$fullname, $dob, $address, $phone, $user_id]);

    $message = "Profile updated successfully!";
}

// Ambil data pengguna dari tabel users
$stmt = $pdo->prepare("SELECT fullname, dob, address, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ARAYA HOME MART</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-5">Profile</h1>

        <?php if (isset($message)): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="profile.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Full Name -->
                <div>
                    <label for="fullname" class="block text-lg font-medium">Full Name</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-lg font-medium">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-lg font-medium">Address</label>
                    <textarea name="address" id="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-lg font-medium">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded-md hover:bg-blue-600">
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>