<?php
session_start();
require_once '../../controllers/EmployeeController.php';
require_once '../../helpers/flash.php';

if (!isset($_SESSION['employee_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

$controller = new EmployeeController();
$bookings   = $controller->getAssignedRequests($_SESSION['employee_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard - YardPro</title>
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
    <h3 class="text-success mb-3">My Assigned Bookings</h3>

    <?php showFlash(); ?>

    <?php if (count($bookings) == 0): ?>
        <div class="alert alert-info">No assigned bookings yet.</div>
    <?php else: ?>
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Service Center</th>
                <th>Service</th>
                <th>Price</th>
                <th>Status</th>
                <th>Scheduled</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?= $b['booking_id'] ?></td>
                <td><?= htmlspecialchars($b['center_name']) ?></td>
                <td><?= htmlspecialchars($b['service_name']) ?></td>
                <td>$<?= $b['price'] ?></td>
                <td><?= htmlspecialchars($b['status']) ?></td>
                <td><?= $b['scheduled_for'] ?></td>
                <td>
                    <form method="POST" action="updateStatus.php">
                        <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">

                        <select name="status" class="form-select form-select-sm">
                            <option value="Requested"   <?= $b['status']=='Requested'?'selected':'' ?>>Requested</option>
                            <option value="Assigned"    <?= $b['status']=='Assigned'?'selected':'' ?>>Assigned</option>
                            <option value="InProgress"  <?= $b['status']=='InProgress'?'selected':'' ?>>InProgress</option>
                            <option value="Completed"   <?= $b['status']=='Completed'?'selected':'' ?>>Completed</option>
                        </select>

                        <button class="btn btn-success btn-sm mt-2 w-100">Update</button>
                    </form>
                </td>
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
