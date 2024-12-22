<?php

include("../includes/db.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

if (isset($_GET["edit_user_id"])) {
    $user_id = $_GET["edit_user_id"];
    
    if ($conn === null) {
        die("Connexion échouée");
    }
    
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Utilisateur non trouvé");
    }
}

$sql_roles = "SELECT * FROM roles";
$result_roles = $conn->query($sql_roles);
$roles = $result_roles->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_user"])) {
    $update_user_role = $_POST["role_id"];
    
    if (empty($update_user_role)) {
        echo "Le rôle ne peut pas être vide.";
    } else {
        $sql = "UPDATE users SET role_id=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $update_user_role, $user_id);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $stmt->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un Utilisateur</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex justify-center items-center min-h-screen">
    <form action="" method="post" class="bg-white rounded-lg shadow-lg p-8 w-1/3">
      <h2 class="text-xl font-bold mb-4">Modifier l'utilisateur</h2>
      
      <label for="role" class="block text-sm font-medium text-gray-700">Rôle de l'utilisateur</label>
      <select 
        name="role_id" 
        id="role_id" 
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm mb-4"
      >
        <?php foreach ($roles as $role): ?>
            <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $user['role_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
      </select>

      <div class="flex justify-end">
        <a href="dashboard.php" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:bg-white dark:text-gray-900 dark:border-gray-200 dark:hover:bg-gray-100 dark:hover:text-blue-700 dark:focus:ring-gray-100">
          Annuler
        </a>
        <button type="submit" name="update_user" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          Enregistrer
        </button>
      </div>
    </form>
  </div>
</body>
</html>
