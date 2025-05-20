<?php
 include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // Vulnerable to SQL injection
    $query = "INSERT INTO users (username, email, mobile, password, security_question, security_answer, wallet_balance) VALUES ('$username', '$email', '$mobile', '$password', '$security_question', '$security_answer', 5000.00)";
    if (mysqli_query($conn, $query)) {
        header("Location: login.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   
</head>
<body>
    <header class="bg-cyan-500 text-white">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between flex-wrap">
        <a href="index.php" class="text-2xl pl-0 font-bold text-black ml-0">Practical Infosec E-Commerce</a>

        <!-- Mobile menu button -->
        <button id="menu-toggle" class="block lg:hidden text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Nav items -->
        <div id="menu" class="w-full lg:flex lg:items-center lg:w-auto hidden mt-4 lg:mt-0">
            <ul class="lg:flex lg:space-x-6 space-y-2 lg:space-y-0">
                <li><a href="index.php" class="block px-3 text-lg py-2 rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Home</a></li>
                <li><a href="shopping.php" class="block px-3 py-2 text-lg rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Shopping</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="profile.php" class="block px-3 py-2 text-lg  rounded font-bold text-black  hover:bg-gray-800 hover:text-white">My Profile</a></li>
                    <li>
                        <a href="become_seller.php?<?php echo session_name() . '=' . session_id(); ?>" 
                           class="block px-3 py-2 rounded text-lg font-bold text-black  hover:bg-gray-800 hover:text-white">Become a Seller</a>
                    </li>
                    <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true'): ?>
                        <li><a href="admin.php" class="block text-lg px-3 py-2 rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="block px-3 text-lg py-2 rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="block px-3 py-2 text-lg rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Login</a></li>
                    <li><a href="register.php" class="block px-3 text-lg py-2 rounded font-bold text-black  hover:bg-gray-800 hover:text-white">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <script>
        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function () {
            const menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });
    </script>
</header>
     <main class="container mx-auto mt-10 px-4">
        <div class="max-w-xl mx-auto bg-white shadow-md rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">Register</h1>
            <form method="post" action="register.php">
                <div class="mb-5">
                    <label for="username" class="block mb-2 font-semibold">Username:</label>
                    <input type="text" name="username" id="username" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2 font-semibold">Email:</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="mb-5">
                    <label for="mobile" class="block mb-2 font-semibold">Mobile:</label>
                    <input type="text" name="mobile" id="mobile" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 font-semibold">Password:</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="mb-5">
                    <label for="security_question" class="block mb-2 font-semibold">Security Question:</label>
                    <select name="security_question" id="security_question" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">What is your favorite color?</option>
                        <option value="2">Who is your favorite in sports?</option>
                        <option value="3">What is your pet name?</option>
                        <option value="4">What is the first school you have attended?</option>
                        <option value="5">Which year were you born?</option>
                        <option value="6">What is your maiden name?</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="security_answer" class="block mb-2 font-semibold">Security Answer:</label>
                    <input type="text" name="security_answer" id="security_answer" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-xl transition duration-300">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
