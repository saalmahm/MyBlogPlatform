<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />

<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usernameOrEmail =$_POST['usernameOrEmail'];
    $password = $_POST['password'];

    echo $usernameOrEmail;
    
    $query = "SELECT * FROM users WHERE username = '$usernameOrEmail' OR email = '$usernameOrEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

        header("Location:./profile.php");
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
}
?>

<section class="bg-gray-50 h-screen">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-[20px] h-full">
      <div class="w-[1/2] bg-white rounded-lg shadow sm:max-w-md xl:p-0">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                  Sign in to your account
              </h1>
              <form class="space-y-4 md:space-y-6" action="#" method="post">
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
                      <input type="email" name="usernameOrEmail" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                      <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <div class="flex items-center justify-between"></div>
                  <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center text-white">Sign in</button>
                  <p class="text-sm font-light text-gray-500">
                      Don’t have an account yet? <a href="signup.php" class="font-medium text-blue-600 hover:underline">Sign up</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>
