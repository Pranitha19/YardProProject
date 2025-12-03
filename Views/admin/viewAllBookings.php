<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: /YardProProject/?route=admin/login');
    exit();
}

$admin     = new AdminController();
$employees = $admin->getAllEmployees();

$sql = "SELECT
            b.booking_id,
            b.employee_id,
            u.first_name,
            u.last_name,
            s.name AS center_name,
            b.service_name,
            b.price,
            b.status,
            b.scheduled_for,
            b.created_at
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN service_centers s ON b.center_id = s.center_id
        ORDER BY b.booking_id DESC";

$stmt     = $pdo->query($sql);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="text-success mb-4">All Bookings</h3>

        <?php showFlash(); ?>

        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Service Center</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Scheduled For</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= $b['booking_id'] ?></td>
                    <td><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></td>
                    <td><?= htmlspecialchars($b['center_name']) ?></td>
                    <td><?= htmlspecialchars($b['service_name']) ?></td>
                    <td>$<?= $b['price'] ?></td>
                    <td><?= htmlspecialchars($b['status']) ?></td>
                    <td><?= $b['scheduled_for'] ?></td>
                    <td><?= $b['created_at'] ?></td>

                    <td>
                        <form method="POST" action="/YardProProject/?route=admin/update-booking">
                            <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">

                            <select name="employee_id" class="form-select form-select-sm mb-2">
                                <option value="">Unassigned</option>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?= $emp['employee_id'] ?>"
                                        <?= $b['employee_id'] == $emp['employee_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="status" class="form-select form-select-sm mb-2">
                                <option value="Requested"   <?= $b['status']=='Requested'?'selected':'' ?>>Requested</option>
                                <option value="Assigned"    <?= $b['status']=='Assigned'?'selected':'' ?>>Assigned</option>
                                <option value="InProgress"  <?= $b['status']=='InProgress'?'selected':'' ?>>InProgress</option>
                                <option value="Completed"   <?= $b['status']=='Completed'?'selected':'' ?>>Completed</option>
                            </select>

                            <button class="btn btn-success btn-sm w-100">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="/YardProProject/?route=admin/home" class="btn btn-secondary mt-3">Back</a>
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
