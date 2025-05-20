<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price']; // This should be passed as a hidden field in the form and is vulnerable to tampering
    $quantity = $_POST['quantity']; // This should also be passed as a form field

    // Calculate the total price (vulnerable to tampering)
    $total_price = $price * $quantity;

    // Store the order in the database (for demonstration purposes)
    $query = "INSERT INTO orders (username, product_name, quantity, total_price) VALUES ('{$_SESSION['username']}', '$product_name', '$quantity', '$total_price')";
    mysqli_query($conn, $query);


    echo '
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Checkout Summary</h2>
    <p class="text-lg text-gray-700 mb-2"><span class="font-semibold">Product:</span> ' . htmlspecialchars($product_name) . '</p>
    <p class="text-lg text-gray-700 mb-2"><span class="font-semibold">Quantity:</span> ' . htmlspecialchars($quantity) . '</p>
    <p class="text-lg text-gray-700 mb-2"><span class="font-semibold">Total Amount:</span> $' . htmlspecialchars($total_price) . '</p>
    <p class="text-lg text-gray-700"><span class="font-semibold">Mode:</span class="text-green-600"> Cash on Delivery</p>
</div>';

// echo "<h2>Checkout Summary</h2>";
//     echo "<p>Product: " . htmlspecialchars($product_name) . "</p>";
//     echo "<p>Quantity: " . htmlspecialchars($quantity) . "</p>";
//     echo "<p>Total Amount: $" . htmlspecialchars($total_price) . "</p>";
//     echo "<p>Mode: Cash on Delivery</p>";
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <div class="container mt-5">
    <a href="shopping.php" class="btn bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">Back to Shopping</a>
</div>
</body>
</html>
