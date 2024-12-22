<?php

include("../includes/db.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

if (isset($_GET["edit_tag_id"])) {
    $tag_id = $_GET["edit_tag_id"];
    
    if ($conn === null) {
        die("Connexion échouée");
    }
    
    // Récupérer les informations du tag choisi
    $sql = "SELECT * FROM tags WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tag = $result->fetch_assoc();

    if (!$tag) {
        die("Tag non trouvé");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_tag"])) {
    $update_tag_name = $_POST["tag_name"];
    
    if (empty($update_tag_name)) {
        echo "Le nom du tag ne peut pas être vide.";
    } else {
        $sql = "UPDATE tags SET name=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $update_tag_name, $tag_id);

        if ($stmt->execute()) {
            header("Location: bord-tags.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du tag: " . $stmt->error;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un Tag</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex justify-center items-center min-h-screen">
    <form action="" method="post" class="bg-white rounded-lg shadow-lg p-8 w-1/3">
      <h2 class="text-xl font-bold mb-4">Modifier le Tag</h2>
      <label for="tag-name" class="block text-sm font-medium text-gray-700">Nom du Tag</label>
      <input 
        type="text" 
        id="tag-name" 
        name="tag_name" 
        value="<?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?>" 
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-4"
      >
      <div class="flex justify-end">
      <a href="bord-tags.php" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:bg-white dark:text-gray-900 dark:border-gray-200 dark:hover:bg-gray-100 dark:hover:text-blue-700 dark:focus:ring-gray-100">
  Annuler
</a>

        <button type="submit" name="update_tag" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          Enregistrer
        </button>
      </div>
    </form>
  </div>
</body>
</html>
