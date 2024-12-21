<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />

<?php
require '../includes/db.php';

session_start();
$userLoggedIn = isset($_SESSION['user_id']); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $role_id = 2; 

    $checkQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "L'utilisateur existe déjà.";
    } else {
        $query = "INSERT INTO users (username, email, password, role_id) VALUES ('$username', '$email', '$password', $role_id)";

        if (mysqli_query($conn, $query)) {
            header("Location:./login.php");
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
}
?>
<header class="flex justify-between p-4">
    <a href="/index.php" id="cars">
        <img src="images/cars.gif" alt="">
    </a>
    <div class="lg:hidden" id="burger-icon">
        <img src="/images/menu.png" alt="Menu">
    </div>
    <div id="sidebar"
        class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
        <div class="flex justify-end p-4">
            <button id="close-sidebar" class="text-3xl">X</button>
        </div>
        <div class="flex flex-col items-center space-y-4 text-white">
            <a href="/home.php" class="text-black text-lg">Home</a>
            <a href="/index.php" class="text-black text-lg">Blog</a>
            <?php if ($userLoggedIn): ?>
                <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <a href="/pages/logout.php" class="text-red-500 text-lg">Log out</a>
            <?php else: ?>
                <a href="/pages/signup.php" class="text-blue-500 text-lg">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hidden lg:flex justify-center space-x-4">
        <ul class="flex items-center text-sm font-medium text-gray-400 mb-0">
            <li>
                <a href="/home.php" class="hover:underline me-4 md:me-6">Home</a>
            </li>
            <li>
                <a href="/index.php" class="hover:underline me-4 md:me-6">Blog</a>
            </li>
            <?php if ($userLoggedIn): ?>
                <li>
                    <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                </li>
                <li>
                    <a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a>
                </li>
                <li>
                    <a href="/pages/logout.php" class="text-red-500 hover:underline me-4 md:me-6">Log out</a>
                </li>
            <?php else: ?>
                <li>
                    <a href="/pages/signup.php" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 me-4 md:me-6">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>
<section class="bg-gray-50 h-screen">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-full">
      <div class="w-[1/2] bg-white rounded-lg shadow sm:max-w-md xl:p-0">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                  Create an account
              </h1>
              <form class="space-y-4 md:space-y-6" action="#" method="post">
                  <div>
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Your name</label>
                      <input type="text" name="username" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
                      <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                      <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center text-white">Create an account</button>
                  <p class="text-sm font-light text-gray-500">
                      Already have an account? <a href="login.php" class="font-medium text-blue-600 hover:underline">Login here</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>

</form>