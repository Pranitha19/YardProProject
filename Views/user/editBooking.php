<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: /YardProProject/?route=user/login");
    exit;
}

$controller = new UserController();

// Get booking_id from query string
$booking_id = $_GET['id'] ?? null;
if (!$booking_id) {
    die("Invalid booking request.");
}

// Fetch booking from model via controller
$booking = $controller->getBookingById($booking_id);
if (!$booking || $booking['user_id'] != $user_id) {
    die("Booking not found or access denied.");
}

$error = "";

// Handle form submission (update booking)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDate = $_POST['booking_date'] ?? '';
    $newTime = $_POST['booking_time'] ?? '';

    if ($newDate === '' || $newTime === '') {
        $error = "Please select both date and time.";
    } else {
        // Rule 1: Date must not be less than today
        $today = date('Y-m-d');
        if ($newDate < $today) {
            $error = "Selected date cannot be before today.";
        } else {
            // Rule 2: Check if slot is already booked (by another booking)
            $isTaken = $controller->isSlotTaken(
                $booking['center_id'],
                $newDate,
                $newTime,
                $booking_id  // exclude this booking itself
            );

            if ($isTaken) {
                $error = "This time slot is already booked. Please choose another slot.";
            } else {
                // ✅ Update only date & time (no auto cancel)
                $controller->updateBooking($booking_id, $newDate, $newTime);

                // PRG: Redirect back to viewBookings with success message
                header("Location: /YardProProject/?route=user/view-bookings&msg=Booking+updated+successfully");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking - YardPro</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/YardProProject/Static/css/user.css">
</head>
<body>

<?php include __DIR__ . '/../shared/navbar.php'; ?>

<div class="book-container mt-4">
  <h2>Edit Booking</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <!-- Service Center (read-only) -->
    <div class="mb-3">
      <label class="form-label">Service Center</label>
      <input type="text" class="form-control"
             value="<?= htmlspecialchars($booking['service_name']) ?>" readonly>
    </div>

    <!-- Current Date -->
    <div class="mb-3">
      <label class="form-label">Current Date</label>
      <input type="text" class="form-control"
             value="<?= htmlspecialchars($booking['booking_date']) ?>" readonly>
    </div>

    <!-- Current Time -->
    <div class="mb-3">
      <label class="form-label">Current Time</label>
      <input type="text" class="form-control"
             value="<?= htmlspecialchars($booking['booking_time']) ?>" readonly>
    </div>

    <!-- New Date -->
    <div class="mb-3">
      <label class="form-label">New Date</label>
      <input type="date" name="booking_date" id="booking_date" class="form-control" required>
    </div>

    <!-- New Time (2-hour slots) -->
    <div class="mb-3">
      <label class="form-label">New Time (2-hour slots)</label>
      <select name="booking_time" id="booking_time" class="form-select" required>
        <option>Select date first</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Booking</button>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const dateInput  = document.getElementById("booking_date");
  const timeSelect = document.getElementById("booking_time");
  const centerId   = "<?= $booking['center_id'] ?>";

  // Min date = today
  const today = new Date().toISOString().split("T")[0];
  dateInput.min = today;

  // When date changes → load available slots for that center & date
  dateInput.addEventListener("change", async () => {
    const selectedDate = dateInput.value;
    if (!selectedDate) return;

    timeSelect.innerHTML = "<option>Loading...</option>";

    try {
      const res = await fetch(
        `/YardProProject/Views/user/fetch_slots.php?center_id=${centerId}&date=${selectedDate}`
      );
      const slots = await res.json();

      timeSelect.innerHTML = "";

      if (!Array.isArray(slots) || slots.length === 0) {
        timeSelect.innerHTML = "<option>No available slots</option>";
        return;
      }

      slots.forEach(slot => {
        const opt = document.createElement("option");
        opt.textContent = slot.slot;
        opt.value       = slot.value;
        if (!slot.available) opt.disabled = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      console.error(e);
      timeSelect.innerHTML = "<option>Error loading slots</option>";
    }
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
