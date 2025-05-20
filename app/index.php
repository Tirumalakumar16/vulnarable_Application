<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to Vulnerable E-Commerce Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


   
</head>

<body class="bg-gray-100">
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
                <li><a href="logout.php" class="block px-3 py-2 text-lg rounded font-bold  bg-red-600 text-white ">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Login</a></li>
                <li><a href="register.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

 <section class="max-w-7xl mx-auto mt-10 px-4 text-center ">
    <h2 class="font-bold text-4xl mb-8">Welcome to Vulnerable E-Commerce Web Application</h2>

    <div class="text-left space-y-4">
        <p class="text-lg">
            This lab is designed for educational purposes to help you understand how to discover and exploit
            vulnerabilities in real-world applications. 
            <span class="font-semibold">Do not attempt</span> these approaches on live applications hosted on the internet without proper consent.
        </p>

        <p class="text-lg">
            This application is designed & maintained by 
            <span class="font-semibold">"Practical Infosec"</span> security community.
            You can find the source image on 
            <a href="https://practicalinfosec.com" 
               class="text-blue-600 underline font-bold hover:text-blue-800 ml-1" 
               target="_blank" 
               rel="noopener noreferrer">
                PRACTICAL INFOSEC
            </a>.
        </p>
    </div>

    <div class="mt-8">
        <img src="images/logo.svg" alt="Logo" class="mx-auto w-60 h-60 object-contain" />
    </div>
</section>



    <footer class="bg-black text-white ">
      <div
        class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-5   gap-25"
      >
        <!-- Brand -->
        <div>
          <h2 class="text-3xl font-extrabold mb-4">
            <a href="index.php" class="whitespace-nowrap">Practical InfoSec</a>
          </h2>
          <p class="text-md text-gray-300 ">
            Practical Cybersecurity Learning Platform.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-xl font-semibold mb-3">Quick Links</h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li>
              <a href="index.php" class="hover:text-white hover:font-semibold"
                >Home</a
              >
            </li>
            <li>
              <a
                href="https://courses.practicalinfosec.com/"
                class="hover:text-white hover:font-semibold"
                >Courses</a
              >
            </li>
            <li>
              <a href="blog.html" class="hover:text-white hover:font-semibold"
                >Blog</a
              >
            </li>
            <li>
              <a
                href="aboutUs.html"
                class="hover:text-white hover:font-semibold"
                >About Us</a
              >
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h3 class="text-xl font-semibold mb-3">Contact</h3>
          <ul class="text-sm text-gray-400 space-y-2">
            <li class="hover:text-white hover:font-semibold">
              <i class="fas fa-phone-alt mr-2"></i> +91-9080432374
            </li>
            <li class="hover:text-white hover:font-semibold whitespace-nowrap">
              <i class="fas fa-envelope mr-2"></i> support@practicalinfosec.com
            </li>
            <li class="hover:text-white hover:font-semibold">
              <i class="fas fa-map-marker-alt mr-2"></i> Hyderabad, India
            </li>
          </ul>
        </div>

        <!-- Social -->
        <div>
          <h3 class="text-lg font-semibold mb-3">Follow Us</h3>
          <div class="flex space-x-8 text-2xl">
            <!-- <a href="https://www.youtube.com/@PracticalInfosec"  target="_blank" class="hover:text-blue-500"
            ><i class="fab fa-facebook-f"></i
          ></a> -->
            <a
              href="https://x.com/Practicainfosec"
              target="_blank"
              class="hover:text-sky-400"
              ><i class="fab fa-twitter"></i
            ></a>
            <a
              href="https://www.youtube.com/@InfosecFolks-Telugu/videos"
              target="_blank"
              class="hover:text-red-500"
              ><i class="fab fa-youtube"></i
            ></a>
            <a
              href="https://www.instagram.com/practicalinfosec/"
              target="_blank"
              class="hover:text-pink-500"
              ><i class="fab fa-instagram"></i
            ></a>
            <a
              href="https://www.linkedin.com/company/practicalinfosec"
              target="_blank"
              class="hover:text-blue-300"
              ><i class="fab fa-linkedin-in"></i
            ></a>
          </div>
        </div>

        <!-- App Download -->
        <div>
          <h3 class="text-lg font-semibold mb-3">Get Our App</h3>
          <a
            href="https://play.google.com/store/apps/details?id=co.shield.jyrvp&hl=en_IN"
            target="_blank"
          >
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
              alt="Get it on Google Play"
              class="h-12 hover:opacity-80 transition-opacity duration-300"
            />
          </a>
        </div>
      </div>

      <div
        class="text-center border-t border-gray-700 py-6 text-sm text-gray-400"
      >
        © 2025 PracticalInfoSec. All rights reserved.
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>

</html>
