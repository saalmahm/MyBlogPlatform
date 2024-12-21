<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php
include('../includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $article_id = $_GET['id'];

    $query = "SELECT * FROM articles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        echo "Article not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $article_id = $_POST['id'];
    $tags = $_POST['tags'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target_dir = '../uploads/';
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $update_query = "UPDATE articles SET title = ?, content = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssi", $title, $content, $image, $article_id);
        } else {
            echo "Error uploading the image.";
            exit;
        }
    } else {
        $update_query = "UPDATE articles SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $title, $content, $article_id);
    }

    if ($stmt->execute()) {
        $delete_tags_query = "DELETE FROM article_tags WHERE article_id = ?";
        $stmt = $conn->prepare($delete_tags_query);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();

        $tag_query = "INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)";
        $tag_stmt = $conn->prepare($tag_query);

        foreach ($tags as $tag_id) {
            $tag_stmt->bind_param("ii", $article_id, $tag_id);
            $tag_stmt->execute();
        }

        header("Location:profile.php");
    } else {
        echo "Error updating article.";
    }
}
?>

<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
    <h1 class="text-2xl font-bold mb-6">Edit Article</h1>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea id="content" name="content" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" id="image" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
        </div>

        <div class="mb-4">
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <select name="tags[]" id="tags" multiple class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <?php
                // Fetch tags for the current article
                $query = "SELECT tags.id, tags.name FROM tags 
                          LEFT JOIN article_tags ON tags.id = article_tags.tag_id 
                          WHERE article_tags.article_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $article_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $selected_tags = [];
                while ($row = $result->fetch_assoc()) {
                    $selected_tags[] = $row['id'];
                }

                $query = "SELECT * FROM tags";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = in_array($row['id'], $selected_tags) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
            <small class="text-gray-500">Hold Ctrl (or Command on Mac) to select multiple tags.</small>
        </div>

        <div class="flex justify-end">
            <a href="profile.php"><button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</button>
            </a>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Article</button>
        </div>
    </form>
</div>
</body>
</html>
