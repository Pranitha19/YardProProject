<?php
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: /YardProProject/?route=user/login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {

    $booking_id = $_POST['cancel_id'];

    // Controller -> Model -> returns string
    $result = $controller->cancelBooking($booking_id);

    switch ($result) {
        case "success":
            setFlash('success', 'Booking cancelled successfully. Your refund will be processed in 5 days.');
            header("Location: /YardProProject/?route=user/view-bookings");
            exit;

        case "past":
            setFlash('warning', 'You can cancel only before 24 hours.');
            header("Location: /YardProProject/?route=user/view-bookings");
            exit;

        case "not_allowed":
            setFlash('danger', 'This booking cannot be cancelled.');
            header("Location: /YardProProject/?route=user/view-bookings");
            exit;

        case "not_found":
            setFlash('danger', 'Booking not found.');
            header("Location: /YardProProject/?route=user/view-bookings");
            exit;

        default:
            setFlash('danger', 'An error occurred.');
            header("Location: /YardProProject/?route=user/view-bookings");
            exit;
    }
}
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

    <?php showFlash(); ?>

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
            
            <a href="/YardProProject/?route=user/edit-booking&id=<?= $b['booking_id'] ?>"
               class="btn btn-warning btn-sm">Edit</a>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Flash message auto-hide
setTimeout(() => {
    const msg = document.querySelector('.flash-message');
    if (msg) {
        msg.style.transition = "opacity 0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 2000);
</script>

</body>
</html>
