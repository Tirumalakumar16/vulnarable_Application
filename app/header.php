<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

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

            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="profile.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">My Profile</a></li>
                <li>
                    <a href="become_seller.php?<?php echo session_name() . '=' . session_id(); ?>" 
                       class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Become a Seller</a>
                </li>
                <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true'): ?>
                    <li><a href="admin.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Login</a></li>
                <li><a href="register.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
</body>
</html>
