<?php
include("../includes/db.php");

session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];

    if ($conn === null) {
        die("La connexion à la base de données a échoué.");
    }

    $sql = "DELETE FROM likes WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $sql = "DELETE FROM comments WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $sql = "DELETE FROM articles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    echo "Utilisateur et toutes ses données ont été supprimés avec succès.";
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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
            <button class="block w-full px-4 py-2 text-sm hover:bg-green-600 text-green-300">Users</button>
            <a href="./bord-tags.php"><button id="categories-btn" class="block w-full px-4 py-2 text-sm hover:bg-purple-600 text-purple-300">Tags</button></a>
            <a href="statistiques.php"><button class="block w-full px-4 py-2 text-sm hover:bg-yellow-600 text-yellow-300">Statistics</button></a>
        </nav>
    </aside>
    <main class="ml-64 p-4 w-full">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold mb-4">Users</h1>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ("../includes/db.php");

                    $sql = "SELECT users.id, users.username, users.email, roles.name AS role_name 
                            FROM users 
                            JOIN roles ON users.role_id = roles.id";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                            echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row['username'] . '</td>';
                            echo '<td class="px-6 py-4">' . $row['email'] . '</td>';
                            echo '<td class="px-6 py-4">' . $row['role_name'] . '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<a href="edit-user.php?edit_user_id=' . $row['id'] . '" class="font-medium text-blue-600 hover:underline pr-4">Edit</a>';
                            echo '<a href="?delete_user_id=' . $row['id'] . '" class="font-medium text-red-600 hover:underline">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center px-6 py-4">No users found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
