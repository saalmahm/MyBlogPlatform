<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

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
        echo "<p class='text-red-500 text-center mt-6'>Article introuvable.</p>";
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
    echo "<p class='text-red-500 text-center mt-6'>Aucun article sélectionné.</p>";
    exit;
}
?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <!-- Article Section -->
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($article_result['title']); ?></h1>
        <p class="text-gray-700 text-lg leading-relaxed"><?= nl2br(htmlspecialchars($article_result['content'])); ?></p>
    </div>

    <!-- Comments Section -->
    <div>
        <h2 class="text-3xl font-semibold text-gray-800 mb-4">Commentaires</h2>
        <?php if ($comments_result->num_rows > 0): ?>
            <div class="space-y-6">
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="bg-white p-5 rounded-lg shadow flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-semibold">
                                <?= strtoupper(substr(htmlspecialchars($comment['username']), 0, 1)); ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-800 font-semibold"><?= htmlspecialchars($comment['username']); ?></p>
                            <p class="text-gray-700 mt-1"><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            <p class="text-gray-500 text-sm mt-2"><?= htmlspecialchars($comment['created_at']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 mt-4">Aucun commentaire pour cet article.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
