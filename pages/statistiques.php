<?php
include("../includes/db.php");

if ($conn === null) {
    die("Connexion échouée");
}

session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

$sql_articles = "SELECT COUNT(*) AS total_articles FROM articles";
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$sql_tags = "SELECT COUNT(DISTINCT tag_id) AS total_tags FROM article_tags"; // Exemple si vous avez une table de liens entre articles et tags

$result_articles = $conn->query($sql_articles);
$result_users = $conn->query($sql_users);
$result_tags = $conn->query($sql_tags);

$total_articles = $result_articles->fetch_assoc()['total_articles'];
$total_users = $result_users->fetch_assoc()['total_users'];
$total_tags = $result_tags->fetch_assoc()['total_tags'];

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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistiques</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<header class="flex justify-between p-4">
    <a href="/index.php" id="cars">
        <img src="images/cars.gif" alt="">
    </a>
    <div class="lg:hidden" id="burger-icon">
        <img src="/images/menu.png" alt="Menu">
    </div>
    <div id="sidebar"
        class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
        <div class="flex justify-end p-4">
            <button id="close-sidebar" class="text-3xl">X</button>
        </div>
        <div class="flex flex-col items-center space-y-4 text-white">
            <a href="/home.php" class="text-black text-lg">Home</a>
            <a href="/index.php" class="text-black text-lg">Blog</a>
            <?php if ($userLoggedIn): ?>
                <?php if ($role == 1): ?> <!-- Admin role -->
                    <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <?php endif; ?>
                <?php if ($role == 2): ?> <!-- User role -->
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
            <li>
                <a href="/home.php" class="hover:underline me-4 md:me-6">Home</a>
            </li>
            <li>
                <a href="/index.php" class="hover:underline me-4 md:me-6">Blog</a>
            </li>
            <?php if ($userLoggedIn): ?>
                <?php if ($role == 1): ?> <!-- Admin role -->
                    <li>
                        <a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if ($role == 2): ?> <!-- User role -->
                    <li>
                        <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                    </li>
                <?php endif; ?>
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

  <div class="flex">
    <aside class="fixed top-0 left-0 w-64 bg-gray-800 text-gray-200 h-screen z-40">
        <div class="p-4 text-center">
            <h2 class="text-2xl font-bold text-white">Manage</h2>
        </div>
        <nav class="mt-6">
            <a href="./bord-articles.php"><button class="block w-full px-4 py-2 text-sm hover:bg-blue-600 text-blue-300">Articles</button></a>
            <a href="./dashboard.php"><button class="block w-full px-4 py-2 text-sm hover:bg-green-600 text-green-300">Users</button>
            </a>
            <a href="./bord-tags.php"><button id="categories-btn" class="block w-full px-4 py-2 text-sm hover:bg-purple-600 text-purple-300">Tags</button></a>
            <a href="statistiques.php"><button class="block w-full px-4 py-2 text-sm hover:bg-yellow-600 text-yellow-300">Statistics</button></a>
        </nav>
    </aside>

    <main class="ml-64 p-4 w-full">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold mb-4">Statistiques du site</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-lg font-medium text-gray-700">Nombre total d'articles :</p>
            <p class="text-2xl font-semibold text-blue-700"><?php echo $total_articles; ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-lg font-medium text-gray-700">Nombre total d'utilisateurs :</p>
            <p class="text-2xl font-semibold text-blue-700"><?php echo $total_users; ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-lg font-medium text-gray-700">Nombre total de tags :</p>
            <p class="text-2xl font-semibold text-blue-700"><?php echo $total_tags; ?></p>
        </div>
    </div>
</main>

  </div>

</body>
</html>
