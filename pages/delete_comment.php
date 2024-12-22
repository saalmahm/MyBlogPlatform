<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$comment_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "DELETE FROM comments WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $comment_id, $user_id);

if ($stmt->execute()) {
    header('Location: manage_comments.php');
    exit;
} else {
    echo "Error deleting comment: " . $stmt->error;
}
?>
