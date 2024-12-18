<?php
include('../includes/db.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
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
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300 ">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">X</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="#Contact" class="text-black text-lg">Blog</a>
                <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <a href="/pages/clients.php" class="text-black text-lg">Log out</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="#Contact" class="hover:underline me-4 md:me-6">Blog</a>
                </li>
                <li>
                    <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                </li>
                <li>
                    <a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Log out</a>
                </li>
            </ul>
        </div>
    </header>
    <section class="bg-blue-200 py-4 relative">
        <div class="px-6 lg:right-2">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-4"> 
                <?php if (isset($_SESSION['username'])) {
                    echo "Welcome, " . htmlspecialchars($_SESSION['username'])."👋​"; 
                } else { 
                    echo "Welcome, Guest "; 
                } ?> 
            </h1>            
            <button class="inline-block bg-blue-600 text-white py-3 px-6 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300" onclick="openModal()">Add an article</button>
        </div>
    </section>
</body>
</html>