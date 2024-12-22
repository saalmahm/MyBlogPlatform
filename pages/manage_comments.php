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


    <section class="mt-10 px-6">
        <h1 class="text-3xl font-bold pb-10">Manage Your Comments</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rounded-lg rtl:text-right text-gray-500 dark:text-gray-400 ">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">Article</th>
            <th scope="col" class="px-6 py-3">Comment</th>
            <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo htmlspecialchars($row['title']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></td>
                <td class="px-6 py-4">
                    <a href="edit_comment.php?id=<?php echo $row['id']; ?>" class="font-medium text-blue-600 hover:underline">Edit</a> | 
                    <a href="delete_comment.php?id=<?php echo $row['id']; ?>" class="font-medium text-red-600 hover:underline">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

        </table>
        <a href="/pages/profile.php" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium text-lg hover:underline transition duration-300 ease-in-out">Back to your profile</a>

    </section>
    </body>
</html>
