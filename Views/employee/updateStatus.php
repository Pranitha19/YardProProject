<?php
session_start();
if (!isset($_SESSION['employee_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once("../../Controllers/UserController.php");
$controller = new UserController();

$employee_id = $_SESSION['employee_id'];
$requests = $controller->getAssignedRequests($employee_id);

if (isset($_POST['update'])) {
    $booking_id = $_POST['booking_id'];
    $status     = $_POST['status'];

    if ($controller->updateRequestStatus($booking_id, $employee_id, $status)) {
        $msg = "Status updated successfully!";
        $requests = $controller->getAssignedRequests($employee_id);
    } else {
        $error = "Failed to update.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Update Status - YardPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../Static/css/styles.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-dark navbar-expand-lg" style="background:#2e7d32;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="viewRequest.php">YardPro Employee</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="editProfile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="text-success mb-3">Update Request Status</h3>

        <?php if (!empty($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label>Select Booking</label>
                <select name="booking_id" class="form-select" required>
                    <option value="">Choose...</option>
                    <?php foreach($requests as $r): ?>
                    <option value="<?= $r['booking_id'] ?>">
                        #<?= $r['booking_id'] ?> — <?= $r['service_name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="In-Progress">In-Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div class="col-12">
                <button name="update" class="btn btn-success">Update</button>
                <a href="viewRequest.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>

</body>

</html>