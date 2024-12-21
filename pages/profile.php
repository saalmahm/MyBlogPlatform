<?php
include('../includes/db.php');
session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $tags = $_POST['tags']; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target_dir ='../uploads/';
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $query = "INSERT INTO articles (title, content, image, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $title, $content, $image, $user_id);

            if ($stmt->execute()) {
                $article_id = $stmt->insert_id;

                $tag_query = "INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)";
                $tag_stmt = $conn->prepare($tag_query);

                foreach ($tags as $tag_id) {
                    $tag_stmt->bind_param("ii", $article_id, $tag_id);
                    $tag_stmt->execute();
                }
            } else {
                echo "Erreur : " . $stmt->error;
            }
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Aucune image téléchargée ou erreur lors du téléchargement.";
    }
}

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
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
    <section class="bg-blue-200 py-4 relative flex justify-between">
        <div class="px-6 lg:right-2">
          <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-4"> 
                <?php if (isset($_SESSION['username'])) {
                    echo "Welcome, " . htmlspecialchars($_SESSION['username']);
                } else { 
                    echo "Welcome, Guest "; 
                } ?> 
            </h1>                      
            <button class="inline-block bg-blue-600 text-white py-3 px-6 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300" onclick="openModal()">+ Add an article</button>
        </div>
    </section>

    <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Add an Article
                            </h3>
                            <div class="mt-2">
                            <form id="articleForm" method="POST" enctype="multipart/form-data">
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" name="title" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
    </div>
    <div class="mb-4">
        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
        <textarea id="content" name="content" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
    </div>
    <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" id="image" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" required>
    </div>
    <div class="mb-4">
        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
        <select id="tags" name="tags[]" multiple class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <?php
            $query = "SELECT * FROM tags";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) ."</option>";
            }
            ?>
        </select>
        <small class="text-gray-500">Hold Ctrl (or Command on Mac) to select multiple tags.</small>
    </div>
    <div class="sm:flex sm:items-center">
        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
            Add Article
        </button>
        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
            Cancel
        </button>
    </div>
</form>


</div>
</div>
 </div>
</div>
 </div>
 </div>
 </div>
<section>
    <div class="container mx-auto px-4 mt-10"> 
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Articles que tu as crée</h2> 
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
             <?php 
              $user_id = $_SESSION['user_id'];
            $query = "SELECT articles.*, 
                   users.username, 
                   GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags 
            FROM articles
            JOIN users ON articles.user_id = users.id
            LEFT JOIN article_tags ON articles.id = article_tags.article_id
            LEFT JOIN tags ON article_tags.tag_id = tags.id
            WHERE articles.user_id = $user_id
            GROUP BY articles.id
        ";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) { 
    echo "<div class='bg-gray-100 rounded-lg shadow-md p-4'>"; 
    echo "<div class='flex justify-between'>";   
    echo "<h3 class='text-xl font-bold mb-2 '>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<div class='flex '>";  
    echo "<button class='text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'>
    <a href='edit_article.php?id=" . $row['id'] . "'>Edit</a>
  </button>";
   echo "<button class='text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900'>
        <a href='delete_article.php?id=" . $row['id'] . "'>Delete</a>
      </button>";
    echo "</div>";
    echo "</div>";                 
    echo "<p class='text-gray-700 mb-4'>" . htmlspecialchars($row['content']) . "</p>";
    echo "<img src='/uploads/" . htmlspecialchars($row['image']) . "' alt='Image de l\'article' class='w-full h-48 object-cover mb-4 rounded-lg'>"; 
    echo "<p class='text-gray-600 text-sm'>Par " . htmlspecialchars($row['username']) . " le " . htmlspecialchars($row['created_at']) . "</p>";
    if (!empty($row['tags'])) {
        echo "<p class='text-blue-600 text-sm'>Tags : " . htmlspecialchars($row['tags']) . "</p>";
    }
    echo "</div>"; 
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
    function openModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
        }
  
</script>
</body>
</html>
