<?php
session_start();
$userLoggedIn = isset($_SESSION['user_id']);

if ($userLoggedIn) {
    $userId = $_SESSION['user_id'];
    
include("./includes/db.php");    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT role_id FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    $role = null;
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $row['role_id']; 
    }
    $conn->close();
}
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
<header class="flex justify-between p-4 fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <a href="/home.php" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
        <img src="/images/icon.png" class="h-8" alt="Flowbite Logo" />
        <span class="text-2xl font-bold whitespace-nowrap dark:text-gray-500"> MyBlogPlatform</span>
    </a>
    <div class="lg:hidden" id="burger-icon">
        <img src="images/menu.png" alt="Menu">
    </div>
    <div id="sidebar" class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
        <div class="flex justify-end p-4">
            <button id="close-sidebar" class="text-3xl">X</button>
        </div>
        <div class="flex flex-col items-center space-y-4 text-white">
            <a href="home.php" class="text-black text-lg">Home</a>
            <a href="index.php" class="text-black text-lg">Blog</a>
            <?php if ($userLoggedIn): ?>
                <?php if ($role == 1): ?>
                    <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <?php endif; ?>
                <?php if ($role == 2): ?>
                    <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <?php endif; ?>
                <a href="/pages/logout.php" class="text-red-500 text-lg">Log out</a>
            <?php else: ?>
                <a href="/pages/signup.php" class="text-blue-500 text-lg">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hidden lg:flex justify-center space-x-4">
        <ul class="flex items-center text-sm font-medium text-gray-400 mb-0">
            <li><a href="home.php" class="hover:underline me-4 md:me-6">Home</a></li>
            <li><a href="index.php" class="hover:underline me-4 md:me-6">Blog</a></li>
            <?php if ($userLoggedIn): ?>
                <?php if ($role == 1): ?>
                    <li><a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a></li>
                <?php endif; ?>
                <?php if ($role == 2): ?>
                    <li><a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a></li>
                <?php endif; ?>
                <li><a href="/pages/logout.php" class="text-red-500 hover:underline me-4 md:me-6">Log out</a></li>
            <?php else: ?>
                <li>
                    <a href="/pages/signup.php" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 me-4 md:me-6">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>


<section class="bg-blue-600 text-white text-center py-20">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold pt-10">Welcome to My Blog</h1>
        <p class="mt-4 text-lg">Discover amazing articles and share your thoughts.</p>
        <a href="index.php" class="inline-block mt-6 bg-white text-blue-600 py-3 px-6 rounded-full font-semibold hover:bg-gray-200 transition">Explore Articles</a>
    </div>
</section>


<section class="bg-gray-100 text-gray-900 py-16" id="about">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center">About Me</h2>
        <p class="mt-4 text-lg text-center">Hi, I'm Salma Hamdi, a passionate web developer. Here are some of the projects I've worked on:</p>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="images/todolist.jpg" alt="Todo List Project" class="w-full h-40 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4">Todo List</h3>
                <p class="text-gray-600 mt-2">TaskFlow is a task management application that allows you to add, search, filter and prioritize tasks.</p>
                <a href="https://saalmahm.github.io/to-do-list/" class="mt-8 text-blue-600 hover:underline">View Project</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="images/fifa.avif" alt="FUTeamBuilder Project" class="w-full h-40 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4">FUTeamBuilder</h3>
                <p class="text-gray-600 mt-2">This interactive app allows you to build your FUT team by adding, positioning and modifying players while respecting the popular tactical formation (4-4-2).</p>
                <a href="https://fu-team-builder.vercel.app/" class="mt-8 text-blue-600 hover:underline">View Project</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="images/rent.jpg" alt="MyBlogPlatform Project" class="w-full h-40 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4">CarRental</h3>
                <p class="text-gray-600 mt-2">This project is to manage customers, cars and rental contracts of a car rental company. Transform your data management with efficiency and accuracy.</p>
                <a href="https://github.com/saalmahm/Car-Rental" class="mt-8 text-blue-600 hover:underline">View Project</a>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="https://github.com/saalmahm" class="inline-block bg-blue-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-500 transition">Learn More About Me</a>
        </div>
    </div>
</section>




<footer class="bg-white rounded-lg shadow dark:bg-gray-900 m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="/home.php" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="/images/icon.png" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"> MyBlogPlatform</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="home.php" class="hover:underline me-4 md:me-6">Home</a>
                </li>
                <li>
                    <a href="/index.php" class="hover:underline me-4 md:me-6">Blog</a>
                </li>
                <li>
                    <a href="#about" class="hover:underline">About</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2024 <a href="https://flowbite.com/" class="hover:underline">MyBlogPlatform™</a>. All Rights Reserved.</span>
    </div>
</footer>
<script>
    const menu = document.getElementById("burger-icon");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("close-sidebar");

    menu.addEventListener("click", () => {
        sidebar.classList.remove("translate-x-full");  
        sidebar.classList.add("translate-x-0");
    });

    closeSidebar.addEventListener("click", () => {
        sidebar.classList.add("translate-x-full");    
        sidebar.classList.remove("translate-x-0");   
    });
</script>

</body> 
</html>
