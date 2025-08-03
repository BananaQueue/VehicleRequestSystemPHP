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
    $stmt = $pdo->prepare("UPDATE vehicles SET status = 'returning' WHERE id = ? AND assigned_to = ?");
    $stmt->execute([$vehicle_id, $user]);
    header("Location: dashboard.php");
}
?>
