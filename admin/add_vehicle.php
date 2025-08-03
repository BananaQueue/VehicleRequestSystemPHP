<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate = $_POST['plate_number'];
    $driver = $_POST['driver_name'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("INSERT INTO vehicles (plate_number, driver_name, make, model, type, status) VALUES (?, ?, ?, ?, ?, 'available')");
    $stmt->execute([$plate, $driver, $make, $model, $type]);
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="modal-container">
    <div class="modal">
        <h2>Add Vehicle</h2>
        <form method="POST">
            <label>Plate Number:</label>
            <input type="text" name="plate_number" required>
            <label>Driver:</label>
            <input type="text" name="driver_name" required>
            <label>Make:</label>
            <input type="text" name="make" required>
            <label>Model:</label>
            <input type="text" name="model" required>
            <label>Type:</label>
            <input type="text" name="type" required>
            <button type="submit">Add Vehicle</button>
        </form>
        <p><a href="../dashboard.php">Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>
