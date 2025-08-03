<?php
session_start(); // Start the session
require 'db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    
    // Verify the user and password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user'] = [
            'id' => $user['id'],
            'role' => $user['role'],
            'name' => $user['name'],
        ];
        
        // Redirect to dashboard
        $_SESSION['show_login_alert'] = true;

        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid email or password."; // Set error message in session
        header("Location: login.php"); // Redirect back to login
        exit;
    }
}
?>
