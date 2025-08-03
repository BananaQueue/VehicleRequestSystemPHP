<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $position = $_POST['position'];
    $role = 'employee';

    $stmt = $pdo->prepare("INSERT INTO users (name, email, position, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $position, $password, $role]);
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="../styles.css">
    
</head>
<body>
<div class="modal-container">
    <div class="modal">
        <h2>Add Employee</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Postion:</label>
            <input type="text" name="position" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Add Employee</button>
        </form>
        <p><a href="../dashboard.php">Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>
