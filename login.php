<?php
session_start(); // Start the session
$error = ''; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Redirect to auth.php for authentication
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['error']) ?></p> <!-- Display error message -->
            <?php unset($_SESSION['error']); // Clear the error after displaying ?>
        <?php endif; ?>

        <form method="POST" action="auth.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
