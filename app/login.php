<?php
session_start();
include 'db.php';

// Generate a new CAPTCHA value (e.g., a random number) on each load
$new_captcha = rand(1000, 9999); // Simple numeric CAPTCHA

// Store the new CAPTCHA and keep history of previous ones
if (!isset($_SESSION['captcha_history'])) {
    $_SESSION['captcha_history'] = [];
}
$_SESSION['captcha_history'][] = $new_captcha;

// Update the current CAPTCHA to be displayed
$_SESSION['captcha'] = $new_captcha;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha'];

    // Vulnerable CAPTCHA validation logic
    if (in_array($captcha_input, $_SESSION['captcha_history'])) {
        // CAPTCHA is correct (even if it's an old one, due to replay attack vulnerability)
        // Proceed with login logic

        // SQL Injection Vulnerability in Login Query
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION['username'] = $user['username'];
            
            // Fetch the domain dynamically based on the host
	    $cookieDomain = ($_SERVER['HTTP_HOST'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : true;

            // Handle insecure deserialization for admin privileges
            $user_preferences = [
                'role' => $user['role']
            ];
            $serialized_preferences = serialize($user_preferences);
            setcookie('user_prefs', $serialized_preferences, time() + (86400 * 30), "/", "", false, false); // 30-day cookie

            // Set is_admin cookie based on user privilege
            $isAdmin = ($user['role'] === 'admin') ? 'true' : 'false';
            setcookie('is_admin', $isAdmin, time() + (86400 * 30), "/", "", false, false); // 30-day cookie

            // Redirect to admin page if the user is an admin
            if ($isAdmin === 'true') {
                header("Location: admin.php");
                exit;
            }

            // Otherwise, redirect to the index page
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid login credentials.";
        }
    } else {
        echo "Invalid CAPTCHA. Please try again.";
    }
}
 ?>
	<?php 
	if (isset($_GET['redirect'])) {
    	    $redirect = $_GET['redirect'];
    	    echo "<script>window.location.href='$redirect';</script>";
    	    exit;
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   
</head>
<body>

 <header class="bg-cyan-500  text-white">
    <nav class="mx-auto px-4 py-2  flex items-center justify-between">
        <!-- Left: Logo -->
        <a href="index.php" class="text-2xl ml-10 font-bold text-black whitespace-nowrap">Practical Infosec E-Commerce</a>

        <!-- Right: Nav items -->
        <ul class="flex space-x-6 items-center">
            <li><a href="index.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Home</a></li>
            <li><a href="shopping.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Shopping</a></li>

       
            
                <li><a href="register.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Register</a></li>
            
        </ul>
    </nav>
</header>
     
   

   <div class="max-w-md mx-auto mt-10 p-8 bg-white shadow-lg rounded-xl">
    <h2 class="text-3xl font-bold text-center text-black mb-6">Login</h2>
    <form method="post" class="space-y-5">
        <!-- Username -->
        <div>
            <label  for="username" class="block text-xl font-semibold text-black mb-1">Username:</label>
            <input  type="text" id="username" name="username" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xl font-semibold text-black mb-1 mt-4">Password:</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- CAPTCHA -->
        <div>
            <label for="captcha" class="block text-xl font-semibold text-black mb-1 mt-4">
                Enter the <strong>CAPTCHA : </strong>  <?php echo $_SESSION['captcha']; ?>
            </label>
            <input type="text" id="captcha" name="captcha" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white mt-5 mb-4 font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </div>

        <!-- Forgot Password Link -->
        <div class="text-center">
            <a href="?redirect=forgot_password.php"
               class="inline-block text-md text-yellow-600 hover:underline hover:text-yellow-700 font-semibold">
                Forgot Password?
            </a>
        </div>
    </form>
</div>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
