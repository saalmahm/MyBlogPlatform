<?php
include("../includes/db.php");

session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

if (isset($_GET['delete_tag_id'])) {
    $tag_id = $_GET['delete_tag_id'];

    if ($conn === null) {
        die("La connexion à la base de données a échoué.");
    }

    $sql = "DELETE FROM article_tags WHERE tag_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    $stmt->execute();

    $sql = "DELETE FROM tags WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    $stmt->execute();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $tagNam=$_POST['tag_name'];
    $sql="insert into tags (name) values (?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $tagNam);
    $stmt->execute();
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
            <a href="./bord-articles.php">
                <button class="block w-full px-4 py-2 text-sm hover:bg-blue-600 text-blue-300">Articles</button>
            </a>
            <a href="./dashboard.php">
                <button class="block w-full px-4 py-2 text-sm hover:bg-green-600 text-green-300">Users</button>
            </a>
            <a href="./bord-tags.php">
                <button id="categories-btn" class="block w-full px-4 py-2 text-sm hover:bg-purple-600 text-purple-300">Tags</button>
            </a>
            <a href="statistiques.php"><button class="block w-full px-4 py-2 text-sm hover:bg-yellow-600 text-yellow-300">Statistics</button></a>

        </nav>
    </aside>
</div>
<main class="ml-64 p-4 ">
       <div class="flex justify-between flex-wrap mb-2">
            <h1 class="text-2xl font-bold mb-4">Tags</h1>
            <button type="button" id="add-tag" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-full text-sm px-5 py-2">
            Add +
        </button>        </div>
        <div class="relative overflow-auto shadow-md sm:rounded-lg">
        <table class="w-full table-auto text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                    <th scope="col" class="px-4 py-2 w-1/2">Tags</th>
                    <th scope="col" class="px-4 py-2 w-1/2">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ("../includes/db.php");

                    $sql = "SELECT * 
                            FROM tags ";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                            echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row['name'] . '</td>';
                            echo '<td class="px-6 py-4 flex text-center">';
                            echo '<a href="edit-tag.php?edit_tag_id=' . $row['id'] . '" class="font-medium text-blue-600 hover:underline pr-6">Edit</a>';
                            echo '<a href="?delete_tag_id=' . $row['id'] . '" class="font-medium text-red-600 hover:underline">Delete</a>';
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
    <div id="form-container" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" method="post">
    <form class="bg-white rounded-lg shadow-lg p-8 w-1/3">
        <h2 class="text-xl font-bold mb-4">Ajouter un Tag</h2>
        <label for="tag-name" class="block text-sm font-medium text-gray-700">Nom du Tag</label>
        <input type="text" id="tag-name" name="tag_name" placeholder="Entrez le tag" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-4">
        <div class="flex justify-end">
            <button type="button" id="close-form" class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md mr-2">
                Annuler
            </button>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-md">
                Ajouter
            </button>
        </div>
    </form>
</div>


<script>
    const addTag = document.getElementById("add-tag");
    const formContainer = document.getElementById("form-container");
    const closeForm = document.getElementById("close-form");

    addTag.addEventListener("click", () => {
        formContainer.classList.remove("hidden");
    });

    closeForm.addEventListener("click", () => {
        formContainer.classList.add("hidden");
    });
</script>
</body>
</html>
