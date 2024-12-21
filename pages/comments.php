<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php
include('../includes/db.php');

if (isset($_GET['article_id'])) {
    $article_id = intval($_GET['article_id']);
    
    // Récupérer les informations de l'article
    $article_query = "SELECT title, content FROM articles WHERE id = ?";
    $stmt = $conn->prepare($article_query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $article_result = $stmt->get_result()->fetch_assoc();

    // Vérifier si l'article existe
    if (!$article_result) {
        echo "<p class='text-red-500'>Article introuvable.</p>";
        exit;
    }

    // Récupérer les commentaires de l'article avec jointure sur les utilisateurs
    $comments_query = "SELECT users.username, comments.content AS comment, comments.created_at 
                       FROM comments 
                       JOIN users ON comments.user_id = users.id 
                       WHERE comments.article_id = ? 
                       ORDER BY comments.created_at DESC";
    $stmt = $conn->prepare($comments_query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $comments_result = $stmt->get_result();
} else {
    echo "<p class='text-red-500'>Aucun article sélectionné.</p>";
    exit;
}
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($article_result['title']); ?></h1>
    <p class="text-gray-700 mb-6"><?= htmlspecialchars($article_result['content']); ?></p>

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Commentaires</h2>
    <?php if ($comments_result->num_rows > 0): ?>
        <div class="space-y-4">
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-gray-800 font-semibold"><?= htmlspecialchars($comment['username']); ?></p>
                    <p class="text-gray-700"><?= htmlspecialchars($comment['comment']); ?></p>
                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($comment['created_at']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Aucun commentaire pour cet article.</p>
    <?php endif; ?>
</div>

</body>
</html>
