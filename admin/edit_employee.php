<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'];
    $update = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
    $update->execute([$username, $id]);
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="modal-container">
    <div class="modal">
        <h2>Edit Employee</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <input type="text" name="position" value="<?= htmlspecialchars($user['position']) ?>" required>
            <button type="submit">Update</button>
        </form>
        <p><a href="../dashboard.php">Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>
