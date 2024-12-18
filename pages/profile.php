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
                <a href="/index.php" class="text-black text-lg">Blog</a>
                <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <a href="/pages/logout.php" class="text-red-500 text-lg">Log out</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="/index.php" class="hover:underline me-4 md:me-6">Blog</a>
                </li>
                <li>
                    <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                </li>
                <li>
                    <a href="/pages/logout.php" class= "text-red-500 hover:underline me-4 md:me-6">Log out</a>
                </li>
            </ul>
        </div>
    </header>
    <section class="bg-blue-200 py-4 relative ">
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
    <section>
    <div class="container mx-auto px-4 mt-10"> 
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
             <?php
            $user_id = $_SESSION['user_id'];
            $query = "SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.user_id = $user_id";            
              $result = mysqli_query($conn, $query); while ($row = mysqli_fetch_assoc($result)) { 
                echo "<div class='bg-gray-100 rounded-lg shadow-md p-4'>"; 
                echo "<h3 class='text-xl font-bold mb-2'>" . htmlspecialchars($row['title']) . "</h3>";
                 echo "<p class='text-gray-700 mb-4'>" . htmlspecialchars($row['content']) . "</p>";
                  echo "<img src='../uploads/" . htmlspecialchars($row['image']) . "' alt='Image de l\'article' class='w-full h-48 object-cover mb-4 rounded-lg'>"; 
                  echo "<p class='text-gray-600 text-sm'>Par " . htmlspecialchars($row['username']) . " le " . htmlspecialchars($row['created_at']) . "</p>";
                   echo "</div>"; } ?> </div> </div>
    </section>
</body>
</html>
