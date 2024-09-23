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
        <a href="add_product.php">
            <div class="flex bg-gray-300 m-5 p-3 items-center">
                <img src="../assets/image/dashboard.png" alt="">
                <p class="ml-7 text-2xl font-bold">Dashboard Admin</p>
            </div>
        </a>
        <table class="ml-5 items-center">
            <tbody>
                <tr class="border">
                    <td class="pl-10"><a href=""><img src="../assets/image/categori.png" alt=""></a></td>
                    <td>
                        <a href="">
                            <p class="text-2xl font-bold px-5">Categories</p>
                        </a>
                    </td>
                </tr>
                <tr class="border">
                    <td class="pl-10"><a href=""><img src="../assets/image/produk.png" alt=""></a></td>
                    <td>
                        <a href="">
                            <p class="text-2xl font-bold px-5">
                                Product
                            </p>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-20">
            <a href="">
                <div class="flex items-center justify-between hover:bg-white mx-28 py-3 rounded-xl gap-7">


                </div>
            </a>
            <a href="">
                <div class="flex items-center justify-center hover:bg-white mx-28 py-3 rounded-xl gap-7">

                    <p class="text-2xl font-bold">Product</p>
                </div>
            </a>
            <a href="">
                <div class="flex items-center justify-center hover:bg-white mx-20 py-3 rounded-xl gap-7">
                    <img src=" ../assets/image/orde.png" alt="">
                    <p class="text-2xl font-bold">View Orders</p>
                </div>
            </a>
            <a href="">
                <div class="flex items-center justify-center hover:bg-white mx-20 py-3 rounded-xl gap-7">
                    <img src=" ../assets/image/reviews.png" alt="">
                    <p class="text-2xl font-bold">View Reviews</p>
                </div>
            </a>
        </div>
    </sidebar>
</body>

</html>