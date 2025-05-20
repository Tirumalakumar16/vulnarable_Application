<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping</title>
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
   <div class="container mx-auto mt-10 px-4">
        <h2 class="text-3xl font-bold text-center mb-8 text-blue-700">Shopping Page</h2>

      <!-- Reflected XSS Vulnerability -->
<div class="flex justify-center">
<form method="get" action="shopping.php" class="bg-white p-6 rounded-lg shadow mb-8 flex flex-row items-end gap-4 flex-wrap">
    <div class="flex flex-col">
        <label for="search_product" class="mb-2 font-semibold">Search by Product Name:</label>
        <input type="text" id="search_product" name="search_product"
            value="<?php echo isset($_GET['search_product']) ? $_GET['search_product'] : ''; ?>"
            class="px-4 w-96 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
    </div>
    <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-lg h-fit">
        Search
    </button>
</form>
    </div>

        <?php
        if (isset($_GET['search_product']) && $_GET['search_product'] !== '') {
            $search_input = $_GET['search_product'];
            echo "<div class='bg-blue-100 text-blue-800 px-4 py-3 rounded mb-6'>Search Results for: " . $search_input . "</div>";
            echo "<script>" . $search_input . "</script>";
        }
        ?>

  <!-- SQL Injection Vulnerability -->
<div class="flex justify-center">
  <form method="get" action="shopping.php"
        class="bg-white p-6 rounded-lg shadow mb-8 flex flex-row items-end gap-4 flex-wrap">
      <div class="flex flex-col">
          <label for="product_id" class="mb-2 font-semibold">Search by Product ID:</label>
          <input type="text" id="product_id" name="product_id"
              class="w-96 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>
      <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-lg h-fit">
          Search
      </button>
  </form>
</div>
        <!-- Product Cards -->
        <div class="max-w-6xl mx-auto grid grid-cols-3  gap-8">
            <?php
            // PHP logic unchanged for product display, SQL injection, and XSS demonstration
            if (isset($_GET['product_id'])) {
                $product_id = $_GET['product_id'];
                $query = "SELECT * FROM products WHERE id = '$product_id'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="bg-white rounded-lg border border-gray-300 shadow-md overflow-hidden">

                        <img src="view_image.php?image=' . urlencode($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="w-65 h-65 object-cover">
                        <div class="p-6">
                            <h5 class="text-xl font-bold text-gray-800 mb-2">' . htmlspecialchars($row['name']) . '</h5>
                            <p class="text-gray-600 mb-4">Price: $' . htmlspecialchars($row['price']) . '</p>
                            <form method="post" action="checkout.php" class="mb-4">
                                <input type="hidden" name="product_name" value="' . $row['name'] . '">
                                <input type="hidden" name="price" value="' . $row['price'] . '">
                                <label for="quantity" class="block mb-1 font-semibold">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" class="w-full px-3 py-2 border rounded-md mb-3">
                                <button type="submit" class="bg-blue-600 text-white font-semibold  px-5 py-2 rounded-md">Buy Now</button>
                            </form>

                            <form method="post" action="shopping.php" class="mb-4">
                                <label for="feedback_' . htmlspecialchars($row['id']) . '" class="block mb-2 font-semibold">Feedback:</label>
                                <textarea id="feedback_' . htmlspecialchars($row['id']) . '" name="feedback_' . htmlspecialchars($row['id']) . '" rows="3"
                                    class="w-full px-3 py-2 border rounded-md mb-2"></textarea>
                                <button type="submit" class="bg-blue-600 text-white font-semibold  px-5 py-2 rounded-md">Submit Feedback</button>
                                <input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">
                            </form>

                            <div>
                                <h6 class="font-semibold mb-2">Customer Feedback:</h6>';
                                $feedback_query = "SELECT * FROM feedback WHERE product_id = '" . $row['id'] . "'";
                                $feedback_result = mysqli_query($conn, $feedback_query);
                                while ($feedback_row = mysqli_fetch_assoc($feedback_result)) {
                                    echo '<p><strong>' . $feedback_row['username'] . '</strong>: ' . $feedback_row['comment'] . '</p>';
                                }
                            echo '</div>
                        </div>
                    </div>';
                }
            } else {
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                   <div class="bg-white rounded-lg border border-gray-300 shadow-md overflow-hidden">

                        <img src="view_image.php?image=' . urlencode($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="w-65 h-65 object-cover">
                        <div class="p-6">
                            <h5 class="text-xl font-bold text-gray-800 mb-2">' . htmlspecialchars($row['name']) . '</h5>
                            <p class="text-gray-600 mb-4">Price: $' . htmlspecialchars($row['price']) . '</p>
                            <form method="post" action="checkout.php" class="mb-4">
                                <input type="hidden" name="product_name" value="' . $row['name'] . '">
                                <input type="hidden" name="price" value="' . $row['price'] . '">
                                <label for="quantity" class="block mb-1 font-semibold">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" class="w-full px-3 py-2 border rounded-md mb-3">
                                <button type="submit" class="bg-blue-600 text-white font-semibold  px-5 py-2 rounded-md">Buy Now</button>
                            </form>

                            <form method="post" action="shopping.php" class="mb-4">
                                <label for="feedback_' . htmlspecialchars($row['id']) . '" class="block mb-2 font-semibold">Feedback:</label>
                                <textarea id="feedback_' . htmlspecialchars($row['id']) . '" name="feedback_' . htmlspecialchars($row['id']) . '" rows="3"
                                    class="w-full px-3 py-2 border rounded-md mb-2"></textarea>
                                <button type="submit" class="bg-blue-600 text-white font-semibold  px-5 py-2 rounded-md">Submit Feedback</button>
                                <input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">
                            </form>

                            <div>
                                <h6 class="font-semibold mb-2">Customer Feedback:</h6>';
                                $feedback_query = "SELECT * FROM feedback WHERE product_id = '" . $row['id'] . "'";
                                $feedback_result = mysqli_query($conn, $feedback_query);
                                while ($feedback_row = mysqli_fetch_assoc($feedback_result)) {
                                    echo '<p><strong>' . $feedback_row['username'] . '</strong>: ' . $feedback_row['comment'] . '</p>';
                                }
                            echo '</div>
                        </div>
                    </div>';
                }
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
                $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
                $feedback = mysqli_real_escape_string($conn, $_POST['feedback_' . $product_id]);
                $query = "INSERT INTO feedback (product_id, comment) VALUES ('$product_id', '$feedback')";
                mysqli_query($conn, $query);
                echo "<script>alert('" . $feedback . "');</script>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

