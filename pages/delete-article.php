<?php
session_start();
include("../includes/db.php");

if (isset($_GET['delete_article_id'])) {
    $articleId = $_GET['delete_article_id'];

    $deleteTagsQuery = "DELETE FROM article_tags WHERE article_id = ?";
    $stmt = $conn->prepare($deleteTagsQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();

    $deleteLikesQuery = "DELETE FROM likes WHERE article_id = ?";
    $stmt = $conn->prepare($deleteLikesQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();

    $deleteCommentsQuery = "DELETE FROM comments WHERE article_id = ?";
    $stmt = $conn->prepare($deleteCommentsQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();

    $deleteArticleQuery = "DELETE FROM articles WHERE id = ?";
    $stmt = $conn->prepare($deleteArticleQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();

    echo "Article, commentaires, likes et tags supprimés avec succès.";

    header("Location: bord-articles.php");
    exit;
} else {
    echo "Aucun article sélectionné pour la suppression.";
}
?>
