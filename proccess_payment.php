// process_payment.php
<?php
session_start();
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    // Proses pembayaran (logika pembayaran di sini)
    // ...

    // Jika pembayaran berhasil
    if ($payment_success) {
        header("Location: order_success.php?id=$order_id");
        exit;
    } else {
        // Jika gagal, tampilkan pesan kesalahan
        header("Location: error.php");
        exit;
    }
}
?>