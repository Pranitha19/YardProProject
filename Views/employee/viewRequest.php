<?php
session_start();
require_once '../../controllers/EmployeeController.php';
require_once '../../helpers/flash.php';

if (!isset($_SESSION['employee_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

$controller = new EmployeeController();
$requests   = $controller->getAssignedRequests($_SESSION['employee_id']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Assigned Requests - YardPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark navbar-expand-lg" style="background:#2e7d32;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="home.php">YardPro Employee</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="editProfile.php">Edit Profile</a></li>
            <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="text-success mb-3">Assigned Requests</h3>

    <?php showFlash(); ?>

    <?php if (count($requests) == 0): ?>
        <div class="alert alert-info">No assigned requests.</div>
    <?php else: ?>
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Center</th>
                <th>Status</th>
                <th>Scheduled</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($requests as $r): ?>
            <tr>
                <td><?= $r['booking_id'] ?></td>
                <td><?= htmlspecialchars($r['center_name']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td><?= htmlspecialchars($r['scheduled_for']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
setTimeout(() => {
    const msg = document.querySelector('.flash-message');
    if (msg) {
        msg.style.transition = "opacity 0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 3000);
</script>

</body>
</html>
