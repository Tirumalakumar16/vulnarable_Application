<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $gst_number = $_POST['gst_number'];
    $product_catalog = $_POST['product_catalog'];

    // Display browser level popup
    echo "<script>alert('Your seller enrollment request has been submitted.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Become a Seller</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    
  
    <script>
        function pingDomain() {
            const domain = document.getElementById('domain').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ping.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('pingOutput').innerHTML = `<pre>${xhr.responseText}</pre>`;
                }
            };
            xhr.send('domain=' + encodeURIComponent(domain));
        }

        function parseXML() {
            const productCatalog = document.getElementById('product_catalog').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'parse_xml.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('xmlOutput').innerHTML = `<pre>${xhr.responseText}</pre>`;
                }
            };
            xhr.send('product_catalog=' + encodeURIComponent(productCatalog));
        }
    </script>
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
                <li><a href="logout.php" class="block px-3 py-2 text-lg rounded font-bold  bg-red-600 text-white ">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Login</a></li>
                <li><a href="register.php" class="block px-3 py-2 text-lg rounded font-bold text-black hover:bg-gray-800 hover:text-white">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
    <main class="max-w-3xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold text-center mb-8">Become a Seller</h1>
    <form method="post" action="become_seller.php" class="space-y-6 bg-white p-6 rounded-xl shadow-md">
        
        <div>
            <label for="full_name" class="block text-xl font-semibold mb-2">Full Name:</label>
            <input type="text" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" name="full_name" id="full_name" required>
        </div>
        
        <div>
            <label for="address" class="block text-xl font-semibold mb-2">Address:</label>
            <input type="text" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" name="address" id="address" required>
        </div>
        
        <div>
            <label for="gst_number" class="block text-xl font-semibold mb-2">GST Number:</label>
            <input type="text" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" name="gst_number" id="gst_number" required>
        </div>
        
        <div>
            <label for="product_catalog" class="block text-xl font-semibold mb-2">Product Catalog (XML):</label>
            <textarea class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" name="product_catalog" id="product_catalog" rows="3" required></textarea>
            <button type="button" class="mt-2 bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800" onclick="parseXML()">Parse XML</button>
            <div id="xmlOutput" class="mt-3 text-sm text-gray-700"></div>
        </div>
        
        <div>
            <label for="domain" class="block text-xl font-semibold mb-2">Website Domain (If already selling online):</label>
            <input type="text" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" name="domain" id="domain" required>
            <button type="button" class="mt-2 bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800" onclick="pingDomain()">Ping Domain</button>
            <div id="pingOutput" class="mt-3 text-sm text-gray-700"></div>
        </div>

        <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg mt-6 font-bold text-xl hover:bg-blue-700 transition duration-200">Submit</button>
    </form>
</main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
