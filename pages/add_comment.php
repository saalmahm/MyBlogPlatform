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
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
    </form>
</div>
</body>
</html>
