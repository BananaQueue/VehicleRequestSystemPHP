<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $action = $_POST['action'];

    if ($action === 'confirm') {
        $stmt = $pdo->prepare("UPDATE vehicles SET status='available', assigned_to=NULL WHERE id=?");
    } else {
        $stmt = $pdo->prepare("UPDATE vehicles SET status='in_use' WHERE id=?");
    }

    $stmt->execute([$vehicle_id]);
    header("Location: dashboard.php");
    exit;
}
?>
