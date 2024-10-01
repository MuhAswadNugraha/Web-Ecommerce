<?php
// Include database connection
include 'includes/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $expiry_date = $_POST['expiry_date'];
    $description = $_POST['description']; // Added this line

    $stmt = $pdo->prepare("INSERT INTO vouchers (code, discount, expiry_date, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$code, $discount, $expiry_date, $description]); // Added this line

    header("Location: view_voucher.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Voucher</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto mt-10">
        <div class="bg-white p-8 rounded shadow-lg">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Add Voucher</h1>

            <form method="POST">
                <div class="mb-4">
                    <label for="code" class="block text-gray-700">Code:</label>
                    <input type="text" name="code" id="code" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description:</label> <!-- Added this line -->
                    <input type="text" name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md" required> <!-- Added this line -->
                </div>
                <div class="mb-4">
                    <label for="discount" class="block text-gray-700">Discount (%):</label>
                    <input type="number" name="discount" id="discount" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="expiry_date" class="block text-gray-700">Expiry Date:</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i> Add Voucher
                </button>
            </form>
        </div>
    </div>
</body>

</html>