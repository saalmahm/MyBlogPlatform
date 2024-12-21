<?php
include("../includes/db.php");

if (isset($_GET['comment_id']) && isset($_GET['article_id'])) {
    $comment_id = $_GET['comment_id'];
    $article_id = $_GET['article_id'];

    $query = "DELETE FROM comments WHERE id = $comment_id";
    
    if (mysqli_query($conn, $query)) {
        // Rediriger vers la page de gestion des commentaires de l'article après suppression
        header('Location: view-or-manage-comments.php?article_id=' . $article_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Rediriger vers la page des articles si les ID ne sont pas trouvés
    header('Location: bord-articles.php');
    exit();
}
?>
