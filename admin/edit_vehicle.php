<?php
session_start();
require '../db.php';

// Check if the user is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get vehicle ID from URL parameter
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../dashboard.php");
    exit;
}

// Fetch the current vehicle data
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
    die("Vehicle not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate = $_POST['plate_number'];
    $driver = $_POST['driver_name'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $status = $_POST['status']; // Get the status from the form

    // Update vehicle in the database
    $update = $pdo->prepare("UPDATE vehicles SET plate_number = ?, driver_name = ?, make = ?, model = ?, type = ?, status = ? WHERE id = ?");
    $update->execute([$plate, $driver, $make, $model, $type, $status, $id]);
    
    // Redirect to the dashboard after successful update
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="modal-container">
    <div class="modal">
        <h2>Edit Vehicle</h2>
        <form method="POST">
            <label>Plate Number:</label>
            <input type="text" name="plate_number" value="<?= htmlspecialchars($vehicle['plate_number']) ?>" required>
            
            <label>Driver:</label>
            <input type="text" name="driver_name" value="<?= htmlspecialchars($vehicle['driver_name']) ?>" required>
            
            <label>Make:</label>
            <input type="text" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
            
            <label>Model:</label>
            <input type="text" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
            
            <label>Type:</label>
            <input type="text" name="type" value="<?= htmlspecialchars($vehicle['type']) ?>" required>
            
            <label>Status:</label>
            <select name="status" required>
                <option value="available" <?= $vehicle['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="assigned" <?= $vehicle['status'] === 'assigned' ? 'selected' : '' ?>>Assigned</option>
                <option value="returning" <?= $vehicle['status'] === 'returning' ? 'selected' : '' ?>>Pending return</option>
            </select>
            
            <button type="submit">Update Vehicle</button>
        </form>
        <p><a href="../dashboard.php">Back to Dashboard</a></p>
    </div>
</div>
</body>
</html>
