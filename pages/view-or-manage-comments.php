<?php
include("../includes/db.php");

if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];

    $query = "
        SELECT 
            comments.id AS comment_id, 
            comments.content AS comment_content, 
            comments.created_at AS comment_created_at, 
            users.username AS comment_author
        FROM 
            comments
        LEFT JOIN 
            users ON comments.user_id = users.id
        WHERE 
            comments.article_id = $article_id
    ";

    $result = mysqli_query($conn, $query);
} else {
    // Rediriger vers la page des articles si aucun article_id n'est trouvé
    header('Location: bord-articles.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View or Manage Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Comments for Article ID: <?php echo $article_id; ?></h2>

<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
<tr>
            <th scope="col" class="px-6 py-3">Author</th>
            <th scope="col" class="px-6 py-3">Content</th>
            <th scope="col" class="px-6 py-3">Created At</th>
            <th scope="col" class="px-6 py-3">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">';
                echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $row['comment_author'] . '</td>';
                echo '<td class="px-6 py-4">' . substr($row['comment_content'], 0, 100) . '...</td>';
                echo '<td class="px-6 py-4">' . $row['comment_created_at'] . '</td>';
                echo '<td class="px-6 py-4">';
                echo '<a href="delete-comment.php?comment_id=' . $row['comment_id'] . '&article_id=' . $article_id . '" class="font-medium text-red-600 hover:underline">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4" class="text-center px-6 py-4">No comments found for this article.</td></tr>';
        }
        ?>
    </tbody>
    </table>



        <div class="mt-6">
            <a href="bord-articles.php" class="text-blue-600 hover:underline">Back to Articles</a>
        </div>
    </div>
</body>
</html>
