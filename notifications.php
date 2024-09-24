<?php
session_start();
include 'includes/database.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $notifications = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto px-6 pt-20">
        <h1 class="font-bold text-4xl pb-5">Notifikasi</h1>
        <ul class="list-disc pl-5">
            <?php foreach ($notifications as $notification): ?>
                <li><?php echo htmlspecialchars($notification['message']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>