<?php

session_start();
$userLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
<header class="flex justify-between p-4">
    <a href="/index.php" id="cars">
        <img src="images/cars.gif" alt="">
    </a>
    <div class="lg:hidden" id="burger-icon">
        <img src="images/menu.png" alt="Menu">
    </div>
    <div id="sidebar"
        class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
        <div class="flex justify-end p-4">
            <button id="close-sidebar" class="text-3xl">X</button>
        </div>
        <div class="flex flex-col items-center space-y-4 text-white">
            <a href="home.php" class="text-black text-lg">Home</a>
            <a href="index.php" class="text-black text-lg">Blog</a>
            <?php if ($userLoggedIn): ?>
                <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <a href="/pages/logout.php" class="text-red-500 text-lg">Log out</a>
            <?php else: ?>
                <a href="/pages/signup.php" class="text-blue-500 text-lg">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hidden lg:flex justify-center space-x-4">
        <ul class="flex items-center text-sm font-medium text-gray-400 mb-0">
            <li>
                <a href="home.php" class="hover:underline me-4 md:me-6">Home</a>
            </li>
            <li>
                <a href="index.php" class="hover:underline me-4 md:me-6">Blog</a>
            </li>
            <?php if ($userLoggedIn): ?>
                <li>
                    <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                </li>
                <li>
                    <a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a>
                </li>
                <li>
                    <a href="/pages/logout.php" class="text-red-500 hover:underline me-4 md:me-6">Log out</a>
                </li>
            <?php else: ?>
                <li>
                    <a href="/pages/signup.php" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 me-4 md:me-6">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>

    <section class="bg-blue-600 text-white text-center py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold">Welcome to My Blog</h1>
            <p class="mt-4 text-lg">Discover amazing articles and share your thoughts.</p>
            <a href="index.php" class="inline-block mt-6 bg-white text-blue-600 py-3 px-6 rounded-full font-semibold hover:bg-gray-200 transition">Explore Articles</a>
        </div>
    </section>
</body>
</html>