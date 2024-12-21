<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<?php
include('./includes/db.php');
session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

if ($userLoggedIn && isset($_GET['like'])) {
    $article_id = $_GET['like'];
    $user_id = $_SESSION['user_id'];
    
    // Vérifie si l'user deja aimer ce article
    $checkLikeQuery = "SELECT * FROM likes WHERE user_id = $user_id AND article_id = $article_id";
    $checkLikeResult = mysqli_query($conn, $checkLikeQuery);

    if (mysqli_num_rows($checkLikeResult) == 0) {
        // ajouter like si l'ser na pas encore aimer
        $likeQuery = "INSERT INTO likes (user_id, article_id) VALUES ($user_id, $article_id)";
        mysqli_query($conn, $likeQuery);
    } else {
        // supprimer like si l'iser deja liker
        $unlikeQuery = "DELETE FROM likes WHERE user_id = $user_id AND article_id = $article_id";
        mysqli_query($conn, $unlikeQuery);
    }
}

if ($userLoggedIn) {
    $userId = $_SESSION['user_id'];

    $sql = "SELECT role_id FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    $role = null;
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $row['role_id']; 
    }
}
?>
<header class="flex justify-between p-4">
    <a href="/index.php" id="cars">
        <img src="images/cars.gif" alt="">
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
                    <!-- Admin, n'affiche pas Dashboard -->
                    <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <?php endif; ?>
                <?php if ($role == 2): ?>
                    <!-- User, n'affiche pas Profile -->
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
                    <!-- Admin, n'affiche pas Dashboard -->
                    <li><a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a></li>
                <?php endif; ?>
                <?php if ($role == 2): ?>
                    <!-- User, n'affiche pas Profile -->
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

<section class="bg-white py-8"> 
    <div class="container mx-auto px-4"> 
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Articles Disponibles</h2> 
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
             <?php 
             $query = "SELECT articles.*, users.username, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags, 
                            (SELECT COUNT(*) FROM likes WHERE article_id = articles.id) AS like_count
                        FROM articles
                        JOIN users ON articles.user_id = users.id
                        LEFT JOIN article_tags ON articles.id = article_tags.article_id
                        LEFT JOIN tags ON article_tags.tag_id = tags.id
                        GROUP BY articles.id";
             $result = mysqli_query($conn, $query);

             if ($result && mysqli_num_rows($result) > 0) {
                 while ($row = mysqli_fetch_assoc($result)) {
                     // Variables protégées contre XSS
                     $title = htmlspecialchars($row['title']);
                     $content = htmlspecialchars($row['content']);
                     $image = htmlspecialchars($row['image']);
                     $username = htmlspecialchars($row['username']);
                     $created_at = htmlspecialchars($row['created_at']);
                     $tags = !empty($row['tags']) ? htmlspecialchars($row['tags']) : '';
                     $like_count = $row['like_count'];
                     
                     // Affichage
             ?>
                    <div class="bg-gray-100 rounded-lg shadow-md p-4">
                        <div class="flex justify-between">
                            <h3 class="text-xl font-bold mb-2"><?= $title; ?></h3>
                        </div>
                        <p class="text-gray-700 mb-4"><?= $content; ?></p>
                        <img src="./uploads/<?= $image; ?>" alt="Image de l'article" class="w-full h-48 object-cover mb-4 rounded-lg">
                        <p class="text-gray-600 text-sm">
                            Par <?= $username; ?> le <?= $created_at; ?>
                        </p>
                        <?php if (!empty($tags)): ?>
                            <p class="text-gray-600 text-sm">Tags : <?= $tags; ?></p>
                        <?php endif; ?>
                        <p class="text-blue-600 text-sm underline">
                            <a href="./pages/comments.php?article_id=<?= $row['id']; ?>">View all comments</a>
                        </p>
                        <p class="text-gray-600 text-sm">Likes: <?= $like_count; ?></p>

                        <div class="flex justify-between mt-4">
                            <a href="?like=<?= $row['id']; ?>" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                    <path d="M3 7H1a1 1 0 0 0-1 1v8a2 2 0 0 0 4 0V8a1 1 0 0 0-1-1Zm12.954 0H12l1.558-4.5a1.778 1.778 0 0 0-3.331-1.06A24.859 24.859 0 0 1 6 6.8v9.586h.114C8.223 16.969 11.015 18 13.6 18c1.4 0 1.592-.526 1.88-1.317l2.354-7A2 2 0 0 0 15.954 7Z"/>
                                </svg>
                            </a>
                            <a href="./pages/add_comment.php?article_id=<?= $row['id']; ?>" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                Post comment
                            </a>  
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p>Aucun article trouvé.</p>";
            }
            ?>
        </div>
    </div>
</section>

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