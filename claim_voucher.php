<?php
// Include database connection
include 'includes/database.php';

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch available vouchers that are active (removing expiry date check for testing)
$stmt = $pdo->query("SELECT * FROM vouchers WHERE is_active = 1 ORDER BY expiry_date ASC");
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: Check what vouchers are retrieved
if (empty($vouchers)) {
    error_log('No active vouchers found for user ID: ' . $user_id);
}

// Handle voucher claim action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['voucher_id'])) {
    $voucher_id = $_POST['voucher_id'];

    // Check if the user has already claimed the voucher
    $checkStmt = $pdo->prepare("SELECT * FROM user_vouchers WHERE user_id = ? AND voucher_id = ?");
    $checkStmt->execute([$user_id, $voucher_id]);
    $alreadyClaimed = $checkStmt->fetch();

    if ($alreadyClaimed) {
        $message = "You have already claimed this voucher!";
    } else {
        // Insert into user_vouchers table
        $claimStmt = $pdo->prepare("INSERT INTO user_vouchers (user_id, voucher_id) VALUES (?, ?)");
        $claimStmt->execute([$user_id, $voucher_id]);
        $message = "Voucher claimed successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Your Vouchers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold mb-6 text-center">Claim Your Vouchers</h1>

        <!-- Display success or error messages -->
        <?php if (!empty($message)): ?>
            <div class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3 mb-4" role="alert">
                <p class="font-bold"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <!-- Show available vouchers -->
        <?php if (!empty($vouchers)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($vouchers as $voucher): ?>
                    <div class="border-2 border-gray-300 rounded-lg p-4 flex flex-col items-center bg-white shadow hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($voucher['code']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($voucher['description']); ?></p>
                        <p class="text-sm text-gray-500 mb-4">Expiry Date: <?php echo htmlspecialchars($voucher['expiry_date']); ?></p>
                        <p class="text-lg font-bold text-green-500 mb-4">Discount: <?php echo htmlspecialchars($voucher['discount']); ?>%</p>

                        <form method="POST" class="w-full">
                            <input type="hidden" name="voucher_id" value="<?php echo $voucher['id']; ?>">
                            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                                <i class="fas fa-gift mr-2"></i>Claim Voucher
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">No vouchers available to claim at the moment.</p>
        <?php endif; ?>
    </div>

    <footer class="bg-gray-200 py-4 mt-10">
        <div class="container mx-auto text-center text-gray-600">
            &copy; 2024 ARAYA HOME MART. All rights reserved.
        </div>
    </footer>
</body>

</html>