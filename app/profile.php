<?php
// Start the session at the very beginning
session_start();
error_reporting(0);  // Disable warnings or errors that interfere with headers
ob_start(); 


// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login if the session is not set
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Retrieve the logged-in user's details
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // If user not found in the database, destroy the session and redirect to login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Initialize variables for user data and messages
$userData = null;
$successMessage = "";
$errorMessage = "";

// Handle IDOR for viewing profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_user_details'])) {
    $user_id = intval($_POST['user_id']); // IDOR vulnerability
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        $errorMessage = "User not found!";
    }
}

// Handle password change (CSRF vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $user_id = intval($_POST['user_id']); // CSRF vulnerability
    $query = "UPDATE users SET password='$new_password' WHERE id=$user_id";
    if (mysqli_query($conn, $query)) {
        $successMessage = "Password updated successfully!";
    } else {
        $errorMessage = "Error updating password: " . mysqli_error($conn);
    }
}

// Handle file upload (Unrestricted File Upload vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    // Ensure the uploads directory exists and set permissions
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);  // Create directory if it doesn't exist
        chmod($target_dir, 0777);        // Ensure the directory has proper permissions
    }

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $successMessage = "File uploaded successfully: " . htmlspecialchars($target_file);
    } else {
        $errorMessage = "Sorry, there was an error uploading your file.";
    }
}


// Handle fetching image from URL (SSRF vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_image'])) {
    $image_url = $_POST['image_url'];
    $image_content = file_get_contents($image_url); // SSRF vulnerability
    if ($image_content) {
        file_put_contents('uploads/remote_image.jpg', $image_content);
        $successMessage = "Image fetched from remote URL and saved as uploads/remote_image.jpg.";
    } else {
        $errorMessage = "Sorry, there was an error fetching the image.";
    }
}

// Simulate storing user preferences in a serialized object
$user_preferences = [
    'theme' => 'dark',
    'notifications' => 'enabled',
    'role' => 'user'
];

$serialized_preferences = serialize($user_preferences);
setcookie('user_prefs', $serialized_preferences, time() + (86400 * 30), "/"); // 30-day cookie

// Deserializing the cookie
if (isset($_COOKIE['user_prefs'])) {
    $deserialized_preferences = unserialize($_COOKIE['user_prefs']);
    
    // Check for role after deserialization (vulnerable to tampering)
    if ($deserialized_preferences['role'] === 'admin') {
        setcookie('is_admin', 'true', time() + (86400 * 30), "/");
        header("Location: admin.php");
        exit;
    }
    
    // Display user preferences
    echo "<h2>Your Preferences:</h2>";
    echo "<p>Theme: " . htmlspecialchars($deserialized_preferences['theme']) . "</p>";
    echo "<p>Notifications: " . htmlspecialchars($deserialized_preferences['notifications']) . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
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
    
    <main class="max-w-3xl mx-auto mt-10 px-6 py-8 bg-white shadow-lg rounded-xl">
    <h1 class="text-3xl text-center font-bold text-gray-800 mb-6">My Profile</h1>

    <h2 class="text-xl font-semibold text-gray-700 mb-4">View Profile Details</h2>
    <form method="post" action="profile.php" class="mb-6">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <button type="submit" name="view_user_details"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
            View Profile Details
        </button>
    </form>

    <?php if ($userData): ?>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Profile Details</h3>
        <p class="text-base text-gray-800"><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
        <p class="text-base text-gray-800"><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
        <p class="text-base text-gray-800"><strong>Mobile:</strong> <?php echo htmlspecialchars($userData['mobile']); ?></p>
        <p class="text-base text-gray-800"><strong>Wallet-Balance:</strong> <?php echo htmlspecialchars($userData['wallet_balance']); ?></p>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php elseif ($errorMessage): ?>
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-4">Change Password</h2>
    <form method="post" action="profile.php" class="mb-6 space-y-4">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <div>
            <label for="new_password" class="block text-md font-medium text-gray-700 mb-1">New Password:</label>
            <input type="password" id="new_password" name="new_password" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" name="change_password"
                class="bg-blue-600 hover:bg-blue-700 text-white mt-4 font-semibold py-2 px-4 rounded-lg transition duration-200">
            Change Password
        </button>
    </form>

    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-4">Update Profile Picture</h2>
    <form method="post" enctype="multipart/form-data" action="profile.php" class="mb-6 space-y-4">
        <div>
            <label for="profile_image" class="block text-md font-medium text-gray-700 mb-1">Upload Photo:</label>
            <input type="file" name="profile_image" id="profile_image"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white mt-4 font-semibold py-2 px-4 rounded-lg transition duration-200">
            Upload
        </button>
    </form>

    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-4">Fetch Image from URL</h2>
    <form method="post" action="profile.php" class="space-y-4">
        <div>
            <label for="image_url" class="block text-md font-medium text-gray-700 mb-1">Remote Image URL:</label>
            <input type="text" id="image_url" name="image_url" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" name="fetch_image"
                class="bg-blue-600 hover:bg-blue-700 mt-4 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
            Fetch Image
        </button>
    </form>
</main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
