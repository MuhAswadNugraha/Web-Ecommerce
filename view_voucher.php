<?php
// Include database connection
include 'includes/database.php';

$stmt = $pdo->query("SELECT * FROM vouchers ORDER BY COALESCE(expiry_date, '9999-12-31') DESC");
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vouchers</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto mt-10">
        <div class="bg-white p-8 rounded shadow-lg">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Vouchers</h1>
            <a href="add_voucher.php" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 inline-flex items-center mb-4">
                <i class="fas fa-plus mr-2"></i> Add Voucher
            </a>

            <table class="table-auto w-full bg-white border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 text-left">
                        <th class="p-4">Code</th>
                        <th class="p-4">Description</th> <!-- Added this line -->
                        <th class="p-4">Discount</th>
                        <th class="p-4">Expiry Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $voucher): ?>
                        <tr class="border-b border-gray-200">
                            <td class="p-4"><?= htmlspecialchars($voucher['code']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($voucher['description']) ?></td> <!-- Added this line -->
                            <td class="p-4"><?= htmlspecialchars($voucher['discount']) ?>%</td>
                            <td class="p-4"><?= htmlspecialchars($voucher['expiry_date']) ?></td>
                            <td class="p-4"><?= $voucher['is_active'] ? '<span class="text-green-600">Active</span>' : '<span class="text-red-600">Inactive</span>' ?></td>
                            <td class="p-4">
                                <a href="edit_voucher.php?id=<?= $voucher['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600 mr-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_voucher.php?id=<?= $voucher['id'] ?>" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>