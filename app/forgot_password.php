<?php
include 'db.php';

$step = 1;
$message = '';
$stored_username = '';
$security_question = '';
$security_answer = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        // Step 1: User submits the username
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $query = "SELECT security_question, security_answer FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $security_question = $user['security_question'];
            $stored_username = $username; // Store the username for later steps
            $security_answer = $user['security_answer'];
            $step = 2;
        } else {
            $message = "Username not found.";
        }
    } elseif (isset($_POST['security_answer']) && isset($_POST['stored_username']) && isset($_POST['security_question'])) {
        // Step 2: User submits the answer to the security question
        $stored_username = mysqli_real_escape_string($conn, $_POST['stored_username']);
        $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
        $submitted_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);

        // Fetch the correct answer from the database
        $query = "SELECT security_answer FROM users WHERE username = '$stored_username'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($submitted_answer === $user['security_answer']) {
                $step = 3; // Correct answer, move to the next step
            } else {
                $message = "Incorrect answer to the security question.";
                $step = 2; // Stay on the same step
            }
        } else {
            $message = "An error occurred. Please try again.";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['stored_username'])) {
        // Step 3: User submits the new password
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $stored_username = mysqli_real_escape_string($conn, $_POST['stored_username']);
        $query = "UPDATE users SET password = '$new_password' WHERE username = '$stored_username'";
        if (mysqli_query($conn, $query)) {
            $message = "Password updated successfully!";
            header("Location: login.php"); // Redirect to login page
            exit;
        } else {
            $message = "Error updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
   

    <div class="container mx-auto mt-10 px-4">
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>

        <?php if ($message): ?>
            <div class="bg-blue-100 text-blue-800 px-4 py-3 rounded mb-4 border border-blue-300">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <form method="post" class="space-y-4">
                <div class="form-group">
                    <label for="username" class="block text-gray-700 font-semibold mb-1">Enter your username:</label>
                    <input type="text" class="form-control w-full max-w-6xl px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="username" name="username" required>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Next</button>
            </form>
            <br>
            <a href='login.php' class="text-blue-600 hover:underline">Go back to Login page</a>

        <?php elseif ($step == 2): ?>
            <form method="post" class="space-y-4">
                <div class="form-group">
                    <label for="security_question" class="block text-gray-700 font-semibold mb-1">Security Question:</label>
                    <input type="text" class="form-control w-full max-w-6xl px-4 py-2 border border-gray-300 rounded bg-gray-100" id="security_question" value="<?php echo htmlspecialchars($security_question); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="security_answer" class="block text-gray-700 font-semibold mb-1">Your Answer:</label>
                    <input type="text" class="form-control w-full max-w-6xl px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="security_answer" name="security_answer" required>
                </div>
                <input type="hidden" name="stored_username" value="<?php echo htmlspecialchars($stored_username); ?>">
                <input type="hidden" name="security_question" value="<?php echo htmlspecialchars($security_question); ?>">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Submit Answer</button>
            </form>
            <br>
            <a href='login.php' class="text-blue-600 hover:underline">Go back to Login page</a>

        <?php elseif ($step == 3): ?>
            <form method="post" class="space-y-4">
                <div class="form-group">
                    <label for="new_password" class="block text-gray-700 font-semibold mb-1">Enter your new password:</label>
                    <input type="password" class="form-control w-full max-w-6xl px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="new_password" name="new_password" required>
                </div>
                <input type="hidden" name="stored_username" value="<?php echo htmlspecialchars($stored_username); ?>">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
