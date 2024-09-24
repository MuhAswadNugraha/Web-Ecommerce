<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <sidebar class="min-h-vh m-10">
        <a href="./dashboard.php">
            <div class="flex bg-gray-300 m-5 p-3 items-center justify-center">
                <img src="../assets/image/dashboard.png" alt="">
                <p class="ml-7 text-2xl font-bold">Dashboard Admin</p>
            </div>
        </a>
        <table class="ml-5 items-center">
            <tbody>
                <tr class="border hover:bg-white">
                    <td class="pl-10 py-5"><a href=""><img src="../assets/image/categori.png" alt=""></a></td>
                    <td>
                        <a href="./view_category.php">
                            <p class="text-2xl font-bold px-5">Categories</p>
                        </a>
                    </td>
                </tr>
                <tr class="border hover:bg-white">
                    <td class="pl-10 py-5"><a href=""><img src="../assets/image/produk.png" alt=""></a></td>
                    <td>
                        <a href="./view_product.php">
                            <p class="text-2xl font-bold px-5">
                                Product
                            </p>
                        </a>
                    </td>
                </tr>
                <tr class="border hover:bg-white">
                    <td class="pl-10 py-5"><a href=""><img src="../assets/image/orde.png" alt=""></a></td>
                    <td>
                        <a href="./view_orders.php">
                            <p class="text-2xl font-bold px-5">
                                View Orders
                            </p>
                        </a>
                    </td>
                </tr>
                <tr class="border hover:bg-white">
                    <td class="pl-10 py-5"><a href=""><img src="../assets/image/reviews.png" alt=""></a></td>
                    <td>
                        <a href="">
                            <p class="text-2xl font-bold px-5">
                                View Reviews
                            </p>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </sidebar>
</body>

</html>