<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT comments.*, articles.title 
          FROM comments 
          JOIN articles ON comments.article_id = articles.id 
          WHERE comments.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="flex justify-between p-4 bg-white shadow-md">
    </header>

    <section class="mt-10 px-6">
        <h1 class="text-3xl font-bold">Manage Your Comments</h1>
        <table class="min-w-full mt-4 table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2">Article</th>
                    <th class="px-4 py-2">Comment</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['content']); ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_comment.php?id=<?php echo $row['id']; ?>" class="text-blue-600">Edit</a> | 
                            <a href="delete_comment.php?id=<?php echo $row['id']; ?>" class="text-red-600">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
