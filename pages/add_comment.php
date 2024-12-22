<?php
include("../includes/db.php");
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit();
}
// Récupérer les données via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $article_id = intval($_POST['article_id']);
    $content = htmlspecialchars($_POST['content']);

    // Validation simple
    if (!empty($content) && $article_id > 0) {
        $query = "INSERT INTO comments (content, user_id, article_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sii', $content, $user_id, $article_id);
        
        if ($stmt->execute()) {
            // Rediriger vers la page d'article
            header("Location: /index.php");
            exit();
        } else {
            $error = "Erreur lors de l'ajout du commentaire.";
        }
    } else {
        $error = "Le contenu du commentaire ne peut pas être vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Ajouter un commentaire</h2>
    <?php if (isset($error)): ?>
        <p class="text-red-500"><?= $error ?></p>
    <?php endif; ?>
    <form action="add_comment.php" method="POST">
        <input type="hidden" name="article_id" value="<?= intval($_GET['article_id']) ?>">
        <div class="mb-4">
            <textarea name="content" rows="4" class="w-full p-2 border rounded-lg" placeholder="Votre commentaire..."></textarea>
        </div>
        <div class="sm:flex sm:items-center">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Poster
        </button>
        <a href="home.php" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel
        </a>
    </div>
    </form>
</div>
</body>
</html>
