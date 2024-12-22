<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$comment_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM comments WHERE id = $comment_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $comment = mysqli_fetch_assoc($result);
} else {
    echo "Comment not found or you do not have permission to edit it.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_content = $_POST['content'];
    $updateQuery = "UPDATE comments SET content = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $new_content, $comment_id);

    if ($stmt->execute()) {
        header('Location: manage_comments.php');
        exit;
    } else {
        echo "Error updating comment: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="flex justify-between p-4 bg-white shadow-md">
    </header>

    <section class="mt-10 px-6">
        <h1 class="text-3xl font-bold">Edit Comment</h1>
        <form method="POST">
            <textarea name="content" class="w-full p-2 mt-4" rows="5"><?php echo htmlspecialchars($comment['content']); ?></textarea>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white">Save Changes</button>
        </form>
    </section>
</body>
</html>
