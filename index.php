<?php include 'includes/header.php';

try {
    // Ambil semua produk
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pisahkan produk berdasarkan kategori
    $tamuProducts = array_filter($products, function ($product) {
        return $product['category_id'] == '2'; // Sesuaikan dengan ID kategori Tamu
    });

    $dapurProducts = array_filter($products, function ($product) {
        return $product['category_id'] == '1'; // Sesuaikan dengan ID kategori Dapur
    });

    $tidurProducts = array_filter($products, function ($product) {
        return $product['category_id'] == '3'; // Sesuaikan dengan ID kategori Dapur
    });

    $mandiProducts = array_filter($products, function ($product) {
        return $product['category_id'] == '4'; // Sesuaikan dengan ID kategori Dapur
    });
} catch (PDOException $e) {
    error_log('Failed to fetch products: ' . $e->getMessage());
    $tamuProducts = [];
    $dapurProducts = [];
}
?>

<main>
    <div class="container mx-auto px-6 pt-24">
        <div class="flex mt-2">
            <a href="#"><img src="assets/image/bg-2.png" style="width: 900px;" alt=""></a>
            <a href="#"><img src="assets/image/1.png" style="width: 185px;" alt=""></a>
            <a href="#"><img src="assets/image/2.png" style="width: 265px;" alt=""></a>
            <a href="#"><img src="assets/image/3.png" style="width: 220px;" alt=""></a>
        </div>

        <h1 class="font-bold text-4xl pb-5 pt-4">Ruang Keluarga & Ruang Tamu</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($tamuProducts as $product): ?>
                <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                    <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-auto object-cover mb-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div class="flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                        <i class="fas fa-shopping-cart mr-5"></i>
                        Tambah Ke Keranjang
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <h1 class="font-bold text-4xl pb-5 pt-4">Dapur</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($dapurProducts as $product): ?>
                <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                    <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-auto object-cover mb-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div class="flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                        <i class="fas fa-shopping-cart mr-5"></i>
                        Tambah Ke Keranjang
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <h1 class="font-bold text-4xl pb-5 pt-4">Kamar Tidur</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($tidurProducts as $product): ?>
                <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                    <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-auto object-cover mb-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div class="flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                        <i class="fas fa-shopping-cart mr-5"></i>
                        Tambah Ke Keranjang
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <h1 class="font-bold text-4xl pb-5 pt-4">Kamar Mandi</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($mandiProducts as $product): ?>
                <div class="border-2 border-black rounded-lg p-4 flex flex-col h-full">
                    <?php if (!empty($product['image']) && file_exists('upload/' . $product['image'])): ?>
                        <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-auto object-cover mb-4">
                    <?php else: ?>
                        <img src="assets/image/default-product.png" alt="Default Image" class="w-full h-32 object-cover mb-4">
                    <?php endif; ?>
                    <div class="flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 flex-grow"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="font-bold my-2">Harga: Rp <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                    <a href="cart.php?action=add&id=<?php echo htmlspecialchars($product['id']); ?>" class="w-3/4 m-auto flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-red-600 transition text-center">
                        <i class="fas fa-shopping-cart mr-5"></i>
                        Tambah Ke Keranjang
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<div class="flex justify-around border-2 mx-32 my-10 rounded-3xl text-center pt-5 pb-10 bg-gray-300">
    <div class="w-1/4">
        <h3 class="text-lg font-bold">Kenapa Memilih Kami?</h3>
        <ul class="text-justify mt-5">
            <li><strong>Produk Lengkap dan Bervariasi :</strong><br> Araya Homemart menyediakan lebih dari 80.000 jenis produk, mulai dari perlengkapan rumah tangga, alat dapur, hingga kebutuhan renovasi, sehingga pelanggan dapat menemukan semua yang mereka butuhkan di satu tempat.</li>
            <li><strong>Kualitas Terjamin :</strong><br>Semua produk yang ditawarkan adalah berkualitas tinggi dan original, memastikan keandalan dan daya tahan dalam penggunaannya.</li>
            <li><strong>Jaringan Luas :</strong><br> Puluhan toko yang tersebar di Indonesia, Araya Homemart mudah dijangkau oleh pelanggan di berbagai daerah.</li>
            <li><strong>Harga Kompetitif :</strong><br> Araya Homemart menawarkan produk dengan harga yang bersaing, sering kali dilengkapi dengan promo menarik untuk memberikan nilai lebih bagi pelanggan.</li>
            <li><strong>Layanan Pelanggan yang Prima :</strong><br> Araya Homemart berkomitmen memberikan pelayanan yang ramah dan profesional untuk memastikan pengalaman berbelanja yang nyaman dan memuaskan.</li>
            <li><strong>Kemudahan Berbelanja :</strong><br> Selain melalui toko fisik, Araya Homemart juga menyediakan layanan belanja online, memudahkan pelanggan berbelanja dari rumah dengan berbagai metode pembayaran yang aman dan pengiriman cepat.</li>
        </ul>
    </div>
    <div class="w-1/4">
        <h3 class="text-lg font-bold">Tentang Kami</h3>
        <p class="text-justify mt-5">Araya Homemart adalah pusat perlengkapan rumah dan gaya hidup paling lengkap, berkualitas, dan orisinal di Indonesia, banyak jenis produk dan puluhan toko yang tersebar di indonesia. Berbelanja berbagai kebutuhan rumah tangga, peralatan dapur, renovasi rumah, perlengkapan kantor dan bisnis, serta produk rumah tangga dari Araya Homemart Indonesia kini lebih mudah.</p>
    </div>

    <div class="w-1/4">
        <h3 class="text-lg font-bold">Hubungi Kami</h3>
        <ul class="text-justify mt-5">
            <li class="flex items-center gap-5 py-2"><a class="text-xl">Senin-Minggu</a></li>
            <li class="flex items-center gap-5 py-2"><a class="text-xl">Pukul 09:00 - 17:00 WITA</a></li>
            <li class="flex items-center gap-5 py-2"><i class="fas fa-envelope text-2xl font-bold"></i><a href="mailto:punyayudha01@gmail.com" class="text-xl">arayahomemart@gmail.com</a></li>
            <li class="flex items-center gap-5 py-2"><i class="fab fa-whatsapp text-green-500 text-3xl font-bold"></i><a href="https://wa.me/1234567890" class="text-xl"> +62 852-5169-9033 </a></li>
            <li class="flex items-center gap-7 py-2"><i class="fas fa-map-marker-alt text-3xl font-bold"></i><a href="https://goo.gl/maps/yourgooglemapslink" class="text-xl"> Lihat di Google Maps </a></li>
        </ul>
    </div>
</div>

</div>

<?php include 'includes/footer.php'; ?>