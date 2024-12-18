<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />

<?php
require '../includes/db.php';

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