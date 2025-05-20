<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_COOKIE['is_admin']) || $_COOKIE['is_admin'] !== 'true') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Check if the user is an admin
if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true') {
    echo "<script>alert('Welcome to the Admin Panel!');</script>";
    // Admin functionalities go here...
} else {
    echo "<script>alert('Access Denied. You are not an admin.')</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <?php include 'header.php'; ?>
   
    <main class="flex justify-center mt-10 px-4 ">
    <div class=" p-8 bg-white rounded-2xl shadow-lg border border-gray-200 m-4 w-full max-w-7xl text-center">
        <h1 class="text-4xl font-bold text-blue-600 mb-6">Admin Dashboard</h1>
        <p class="text-lg mb-4">
            Welcome, <strong class="text-black"><?php echo $_SESSION['username']; ?></strong>! 
            You have <span class="text-green-600 font-semibold">admin access</span>.
        </p>
        <p class="text-lg">
            Here you can manage the application and view 
            <span class="text-red-600 font-bold">sensitive information</span>.
        </p>
    </div>
</main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
