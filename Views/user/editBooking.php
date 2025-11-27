<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../config/pdo.php';

// The controller now provides $booking and (optionally) $error via include
if (!isset($booking)) {
    die("Invalid access. Please use the proper route.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking - YardPro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../static/css/user.css">
  </head>
<body>


 <?php include __DIR__ . '/../shared/navbar.php'; ?>

<div class="book-container mt-4">
  <h2>Edit Booking</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form 
    method="post" 
    action="/YardProProject/Controllers/UserController.php?action=editBooking&booking_id=<?= $booking['booking_id'] ?>"
  >
    <div class="mb-3">
      <label class="form-label">Service Center</label>
      <input type="text" class="form-control" 
             value="<?= htmlspecialchars($booking['service_name']) ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Date</label>
      <input type="text" class="form-control" 
             value="<?= htmlspecialchars($booking['booking_date']) ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Time</label>
      <input type="text" class="form-control" 
             value="<?= htmlspecialchars($booking['booking_time']) ?>" readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">New Date</label>
      <input type="date" name="booking_date" id="booking_date" class="form-control" required>
    </div>

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
  const dateInput = document.getElementById("booking_date");
  const timeSelect = document.getElementById("booking_time");
  const centerId = "<?= $booking['center_id'] ?>";
  const today = new Date().toISOString().split("T")[0];
  dateInput.setAttribute("min", today);

  // Load available slots dynamically
  dateInput.addEventListener("change", async () => {
    const selectedDate = dateInput.value;
    if (!selectedDate) return;
    timeSelect.innerHTML = "<option>Loading...</option>";

    try {
      const res = await fetch(`/YardProProject/Views/user/fetch_slots.php?center_id=${centerId}&date=${selectedDate}`);
      const data = await res.json();
      timeSelect.innerHTML = "";
      if (data.length === 0) {
        timeSelect.innerHTML = "<option>No available slots</option>";
        return;
      }
      data.forEach(slot => {
        const opt = document.createElement("option");
        opt.textContent = slot.slot;
        opt.value = slot.value;
        if (!slot.available) opt.disabled = true;
        timeSelect.appendChild(opt);
      });
    } catch (err) {
      console.error("Error loading slots:", err);
      timeSelect.innerHTML = "<option>Error loading slots</option>";
    }
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
