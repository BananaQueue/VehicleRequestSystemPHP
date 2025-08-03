<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $stmt = $pdo->prepare("UPDATE vehicles SET status = 'available', assigned_to = NULL WHERE id = ?");
    $stmt->execute([$vehicle_id]);
    header("Location: dashboard.php");
}
?>
