<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}

$vehicle_id = $_GET['id'] ?? null;
$user = $_SESSION['user']['name'];

if ($vehicle_id) {
    $stmt = $pdo->prepare("UPDATE vehicles SET status = 'assigned', assigned_to = ? WHERE id = ?");
    $stmt->execute([$user, $vehicle_id]);
    header("Location: dashboard.php");
}
?>
<?php

