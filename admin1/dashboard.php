<?php       
include '../includes/database.php';
include 'includes/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: logina.php'); // Redirect to login page
    exit;
}
?>

<div class="flex">
    <div class="min-h-screen bg-gray-100 w-96">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="container mx-auto px-6 pt-20 w-4/5">
        <div class="flex justify-around">
            <div class="bg-blue-300 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/produk.png" alt="">
                    <p>Total Product</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold">350</p>
                <hr class="border-t-2 border-black">
                <a href="">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
            <div class="bg-green-200 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/orde.png" alt="">
                    <p>Total Orders</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold">20</p>
                <hr class="border-t-2 border-black">
                <a href="">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
            <div class="bg-blue-300 rounded-lg">
                <div class="flex items-center gap-5 font-bold text-xl p-2 pl-10 pr-20">
                    <img src="../assets/image/review.png" alt="">
                    <p>Total Reviews</p>
                </div>
                <hr class="border-t-2 border-black">
                <p class="py-16 text-center text-2xl font-bold">2</p>
                <hr class="border-t-2 border-black">
                <a href="">
                    <p class="items-center font-bold text-right pt-1 pr-2">More Info <span><i class="fa-solid fa-circle-right"></i></span></p>
                </a>
            </div>
        </div>
    </div>
</div>
</body>

</html>