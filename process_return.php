<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$vehicle_id = $_GET['id'] ?? null;

if ($vehicle_id) {
    $stmt = $pdo->prepare("UPDATE vehicles SET status = 'available', assigned_to = NULL WHERE id = ?");
    $stmt->execute([$vehicle_id]);
    header("Location: dashboard.php");
}
?>
