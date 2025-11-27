<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$user_id = $_SESSION['user_id'];
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
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        
      <thead>
        <tr style="background:#1b5e20; color:white;">
          <th>center id</th>
          <th>Service center Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Price</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($bookings as $b): ?>
        <tr>
          <td><?= htmlspecialchars($b['center_id']) ?></td>
          <td><?= htmlspecialchars($b['center_name']) ?></td>
          <td><?= htmlspecialchars($b['booking_date']) ?></td>
          <td><?= htmlspecialchars($b['booking_time']) ?></td>
          <td>$<?= htmlspecialchars($b['price']) ?></td>
          <td><?= htmlspecialchars($b['status']) ?></td>
          <td>
  <?php
    // Combine date + time into one timestamp
    $bookingDateTime = strtotime($b['booking_date'] . ' ' . $b['booking_time']);
    $now = time();
    $hoursDiff = ($bookingDateTime - $now) / 3600;

    if ($b['status'] === 'Requested' && $hoursDiff > 24): ?>
         <a href="/YardProProject/Controllers/UserController.php?action=editBooking&booking_id=<?= $b['booking_id'] ?>" class="btn-edit">Edit</a>
        <a href="../../Controllers/UserController.php?action=cancelBooking&booking_id=<?= $b['booking_id'] ?>" class="btn-cancel">Cancel</a>

    <?php elseif ($b['status'] === 'Cancelled'): ?>
        <span class="cancelled">Cancelled</span>

    <?php elseif ($b['status'] === 'Assigned'): ?>
        <span class="assigned">
          Employee Assigned: 
          <?= htmlspecialchars(trim(($b['employee_first_name'] ?? '') . ' ' . ($b['employee_last_name'] ?? ''))) ?>
        </span>

    <?php elseif ($b['status'] === 'InProgress'): ?>
        <span class="in-progress">In Progress</span>

    <?php elseif ($b['status'] === 'Completed'): ?>
        <span class="completed">Completed</span>

    <?php else: ?>
        <span class="disabled">Cannot modify</span>
    <?php endif; ?>
</td>

            
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php if (isset($_GET['toast']) && $_GET['toast'] === 'cancel_success'): ?>
  <!-- ✅ Bootstrap Toast (top-right corner) -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div id="cancelToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Your refund will be processed within 5 days.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
  // ✅ Show toast if URL has ?toast=cancel_success
  document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get("toast") === "cancel_success") {
      const toastEl = document.getElementById("cancelToast");
      const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
      toast.show();
    }
  });
</script>

<?php elseif (isset($_GET['toast']) && $_GET['toast'] === 'late_cancel'): ?>
  <div class="toast align-items-center text-bg-warning border-0 show" role="alert" style="position: fixed; top: 20px; right: 20px;">
    <div class="d-flex">
      <div class="toast-body">You can cancel only before 24 hours from booking time.</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
