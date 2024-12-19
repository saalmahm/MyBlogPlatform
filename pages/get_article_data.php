<?php
include('../includes/db.php');
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    $query = "
        SELECT articles.*, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags 
        FROM articles
        LEFT JOIN article_tags ON articles.id = article_tags.article_id
        LEFT JOIN tags ON article_tags.tag_id = tags.id
        WHERE articles.id = ?
        GROUP BY articles.id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        echo json_encode($article);
    } else {
        echo json_encode([]);
    }
}
?>
