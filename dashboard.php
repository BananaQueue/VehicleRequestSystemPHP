<?php
session_start();
require 'db.php';

if (isset($_SESSION['login_success'])) {
    $show_alert = true;
    unset($_SESSION['login_success']); // Clear immediately after setting
} else {
    $show_alert = false;
}

$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
$isEmployee = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'employee';
$username = $_SESSION['user']['name'] ?? null;

// Fetch vehicles ordered by availability
$stmt = $pdo->query("SELECT * FROM vehicles ORDER BY 
    CASE WHEN status = 'available' THEN 0 ELSE 1 END, plate_number ASC");
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If admin, fetch all employees
$employees = [];
if ($isAdmin) {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'employee'");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Request Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="modal-container">
    <div class="modal">
        <header class="modal-header">
            <h2>Vehicle Request System</h2>
            <div class="auth-links">
                <?php if ($username): ?>
                    <span>Welcome, <?= htmlspecialchars($username) ?>!</span>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </header>

        <section class="vehicle-section">
            <div class="section-header">
                <h3>Available Vehicles</h3>
                <?php if ($isAdmin): ?>
                    <a href="admin/add_vehicle.php" class="action-button">Add Vehicle</a>
                <?php endif; ?>
            </div>
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Driver</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr class="<?=
            $vehicle['status'] === 'available' ? 'available' : 
            ($vehicle['status'] === 'assigned' ? 'assigned' : 
            ($vehicle['status'] === 'returning' ? 'unavailable' : 'unavailable'))
        ?>">
                        <td><?= htmlspecialchars($vehicle['plate_number']) ?></td>
                        <td><?= htmlspecialchars($vehicle['driver_name']) ?></td>
                        <td><?= htmlspecialchars($vehicle['assigned_to'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($vehicle['status']) ?></td>
                        <td>
                            <?php if ($isAdmin): ?>
                                <a href="admin/edit_vehicle.php?id=<?= $vehicle['id'] ?>">Edit</a> |
                                <a href="admin/delete_vehicle.php?id=<?= $vehicle['id'] ?>" onclick="return confirm('Delete vehicle?')">Delete</a>
                            <?php elseif ($isEmployee && $vehicle['status'] === 'available'): ?>
                                <a href="request_vehicle.php?id=<?= $vehicle['id'] ?>" class="btn-request">Request Vehicle</a>
                            <?php elseif ($vehicle['status'] === 'assigned' && $vehicle['assigned_to'] === $_SESSION['user']['name']): ?>
                                <a href="return_vehicle.php?id=<?= $vehicle['id'] ?>" class="btn-return">Return Vehicle</a>
                            <?php elseif (!isset($_SESSION['user']) && $vehicle['status'] === 'available'): ?>
                                <a href="login.php" class="btn-request">Request Vehicle</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <?php if ($isAdmin): ?>
        <section class="employee-section">
            <div class="section-header">
                <h2>Employee Management</h2>
                <a href="admin/add_employee.php" class="action-button">Add Employee</a>
            </div>
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['name']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td><?= htmlspecialchars($emp['position']) ?></td>
                            <td>
                                <a href="admin/edit_employee.php?id=<?= $emp['id'] ?>">Edit</a> |
                                <a href="admin/delete_employee.php?id=<?= $emp['id'] ?>" onclick="return confirm('Delete employee?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php if (isset($_SESSION['show_login_alert'])): ?>
<script>
    alert('Login successful! Welcome back, <?= htmlspecialchars($username ?? 'User') ?>');
</script>
<?php 
    unset($_SESSION['show_login_alert']); // Clear the flag so it doesn't show again
endif; 
?>

</body>
</html>
