<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    
    $check_query = "SELECT * FROM articles WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $delete_tags_query = "DELETE FROM article_tags WHERE article_id = ?";
        $stmt_tags = $conn->prepare($delete_tags_query);
        $stmt_tags->bind_param("i", $article_id);
        $stmt_tags->execute();

        $delete_article_query = "DELETE FROM articles WHERE id = ?";
        $stmt_article = $conn->prepare($delete_article_query);
        $stmt_article->bind_param("i", $article_id);
        if ($stmt_article->execute()) {
            header('Location: ./profile.php'); 
            exit;
        } else {
            echo "Erreur lors de la suppression de l'article.";
        }
    } else {
        echo "Article introuvable.";
    }
} else {
    header('Location: ./profile.php'); }
?>
