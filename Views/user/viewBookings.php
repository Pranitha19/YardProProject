<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit;
}

/* -------------------------------------------------------
      CANCEL BOOKING HANDLER (POST)
--------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {

    $booking_id = $_POST['cancel_id'];

    // Controller -> Model -> returns string
    $result = $controller->cancelBooking($booking_id);

    switch ($result) {
        case "success":
            header("Location: viewBookings.php?toast=cancel_success");
            exit;

        case "past":
            header("Location: viewBookings.php?toast=late_cancel");
            exit;

        case "not_allowed":
            header("Location: viewBookings.php?toast=not_allowed");
            exit;

        case "not_found":
            header("Location: viewBookings.php?toast=not_found");
            exit;

        default:
            header("Location: viewBookings.php?toast=error");
            exit;
    }
}

/* -------------------------------------------------------
      FETCH USER BOOKINGS
--------------------------------------------------------*/
$bookings = $controller->getUserBookings($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - YardPro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/YardProProject/Static/css/user.css">

</head>
<body>

<?php include __DIR__ . '/../shared/navbar.php'; ?>

<div class="booking-container">
    <h2>My Bookings</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <p>No bookings found.</p>
    <?php else: ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Center ID</th>
                <th>Service Center</th>
                <th>Date</th>
                <th>Time</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($bookings as $b): ?>
            <?php
                $bookingDateTime = strtotime($b['booking_date'] . ' ' . $b['booking_time']);
                $hoursLeft = ($bookingDateTime - time()) / 3600;
            ?>
            <tr>
                <td><?= $b['center_id'] ?></td>
                <td><?= htmlspecialchars($b['center_name']) ?></td>
                <td><?= $b['booking_date'] ?></td>
                <td><?= $b['booking_time'] ?></td>
                <td>$<?= $b['price'] ?></td>
                <td><?= $b['status'] ?></td>

                <td>
    <?php if ($b['status'] === 'Requested'): ?>

        <?php if ($hoursLeft > 24): ?>
            <!-- EDIT -->
            <a href="/YardProProject/Views/user/editBooking.php?id=<?= $b['booking_id'] ?>"
               class="btn btn-warning btn-sm">Edit</a>

            <!-- CANCEL -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="cancel_id" value="<?= $b['booking_id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Cancel this booking?');">
                    Cancel
                </button>
            </form>

        <?php else: ?>
            <span class="disabled">Less than 24 hours</span>
        <?php endif; ?>

    <?php elseif ($b['status'] === 'Cancelled'): ?>
        <span class="cancelled">Cancelled</span>

    <?php elseif ($b['status'] === 'Assigned'): ?>
        <span class="assigned">Assigned: <?= htmlspecialchars($b['employee_name']) ?></span>

    <?php elseif ($b['status'] === 'InProgress'): ?>
        <span class="in-progress">In Progress</span>

    <?php elseif ($b['status'] === 'Completed'): ?>
        <span class="completed">Completed</span>

    <?php else: ?>
        <span class="disabled">Not Allowed</span>
    <?php endif; ?>


                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>
    <?php endif; ?>
</div>

<!-- SUCCESS TOAST -->
<?php if (isset($_GET['toast']) && $_GET['toast'] === 'cancel_success'): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index:1055;">
    <div id="cancelToast" class="toast text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">Your refund will be processed in 5 days.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    new bootstrap.Toast(document.getElementById("cancelToast")).show();
});
</script>
<?php endif; ?>

<!-- LATE CANCEL TOAST -->
<?php if (isset($_GET['toast']) && $_GET['toast'] === 'late_cancel'): ?>
<div class="toast show text-bg-warning border-0"
     style="position: fixed; top:20px; right:20px;">
    <div class="d-flex">
        <div class="toast-body">You can cancel only before 24 hours.</div>
        <button type="button" class="btn-close me-2 m-auto"></button>
    </div>
</div>
<?php endif; ?>

<!-- NOT ALLOWED TOAST -->
<?php if (isset($_GET['toast']) && $_GET['toast'] === 'not_allowed'): ?>
<div class="toast show text-bg-danger border-0"
     style="position: fixed; top:20px; right:20px;">
    <div class="d-flex">
        <div class="toast-body">This booking cannot be cancelled.</div>
        <button type="button" class="btn-close me-2 m-auto"></button>
    </div>
</div>
<?php endif; ?>

<!-- NOT FOUND -->
<?php if (isset($_GET['toast']) && $_GET['toast'] === 'not_found'): ?>
<div class="toast show text-bg-danger border-0"
     style="position: fixed; top:20px; right:20px;">
    <div class="d-flex">
        <div class="toast-body">Booking not found.</div>
        <button type="button" class="btn-close me-2 m-auto"></button>
    </div>
</div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
