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
</body>
</html>
