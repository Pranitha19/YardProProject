<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../config/pdo.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header('Location: login.php');
  exit;
}

$center_id = $_POST['center_id'] ?? null;
if (!$center_id) {
  die("Invalid service center.");
}

// Fetch service center details
$stmt = $pdo->prepare("SELECT * FROM service_centers WHERE center_id = :cid");
$stmt->execute([':cid' => $center_id]);
$center = $stmt->fetch();

if (!$center) {
  die("Service center not found.");
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $price = $_POST['price'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $card_holder = $_POST['card_holder'];
    $card_number = $_POST['card_number'];
    $card_type = $_POST['card_type'];
    $cvv = $_POST['cvv'];
    $exp_month = $_POST['exp_month'];
    $exp_year = $_POST['exp_year'];
    $expiry_date = sprintf('%02d/%s', $exp_month, substr($exp_year, -2)); // MM/YY format

    // Prevent overbooking
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM bookings 
        WHERE center_id = :cid AND booking_date = :bdate AND booking_time = :btime
    ");
    $check->execute([':cid' => $center_id, ':bdate' => $booking_date, ':btime' => $booking_time]);
    if ($check->fetchColumn() >= 2) {
        $error = "This time slot is already full. Please choose another.";
    } else {
        // Insert booking
        $insertBooking = $pdo->prepare("
            INSERT INTO bookings (user_id, center_id, service_name, price, booking_date, booking_time)
            VALUES (:uid, :cid, :service_name, :price, :bdate, :btime)
        ");
        $insertBooking->execute([
            ':uid' => $user_id,
            ':cid' => $center_id,
            ':service_name' => $center['name'],
            ':price' => $price,
            ':bdate' => $booking_date,
            ':btime' => $booking_time
        ]);

        $booking_id = $pdo->lastInsertId();

        // Insert payment
        $insertPayment = $pdo->prepare("
            INSERT INTO payments (booking_id, amount, card_holder, card_number, card_type, cvv, expiry_date)
            VALUES (:bid, :amt, :ch, :cn, :ct, :cvv, :exp)
        ");
        $insertPayment->execute([
            ':bid' => $booking_id,
            ':amt' => $price,
            ':ch' => $card_holder,
            ':cn' => $card_number,
            ':ct' => $card_type,
            ':cvv' => $cvv,
            ':exp' => $expiry_date
        ]);

        header("Location: viewBookings.php?msg=Booking+successful");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Service - YardPro</title>
  <link rel="stylesheet" href="../../static/css/user.css">
</head>
<body>

  <?php include __DIR__ . '/../shared/navbar.php'; ?>

  <div class="book-container">
    <h2>Book Service</h2>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" id="bookingForm">
      <label>Center ID</label>
      <input type="text" name="center_id" value="<?= htmlspecialchars($center['center_id']) ?>" readonly>

      <label>Service Name</label>
      <input type="text" name="service_name" value="<?= htmlspecialchars($center['name']) ?>" readonly>

      <label>Price</label>
      <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($center['base_price']) ?>" required>

      <label>Date</label>
      <input type="date" name="booking_date" id="booking_date" required>

      <label>Timing (2-hour slots)</label>
      <select name="booking_time" id="booking_time" required>
        <option>Select date first</option>
      </select>

      <h3>Payment Details</h3>

      <label>Card Type</label>
      <select name="card_type" id="card_type" required>
        <option value="">Select Type</option>
        <option value="Visa">Visa</option>
        <option value="MasterCard">MasterCard</option>
        <option value="Debit">Debit</option>
        <option value="Credit">Credit</option>
      </select>

      <label>Card Holder</label>
      <input type="text" name="card_holder" required>

      <label>Card Number</label>
      <input type="text" id="card_number" name="card_number" maxlength="16" required placeholder="Enter 16-digit card number">

      <label>CVV</label>
      <input type="text" id="cvv" name="cvv" maxlength="3" required>

      <label>Expiry Date</label>
      <div style="display:flex; gap:10px;">
        <select name="exp_month" id="exp_month" required></select>
        <select name="exp_year" id="exp_year" required></select>
      </div>
      <p id="exp_error" class="error" style="display:none;">Enter valid card expiry date</p>

      <button type="submit" name="confirm_booking" class="btn-primary">Pay & Book</button>
    </form>
  </div>

  <script>
  // === AUTO LOAD AVAILABLE SLOTS ===
  document.addEventListener("DOMContentLoaded", () => {
    const dateInput = document.getElementById("booking_date");
    const timeSelect = document.getElementById("booking_time");
    const centerId = "<?= $center_id ?>";

    // Restrict min date = today
    const today = new Date().toISOString().split("T")[0];
    dateInput.setAttribute("min", today);

    dateInput.addEventListener("change", async () => {
      const selectedDate = dateInput.value;
      if (!selectedDate) return;
      timeSelect.innerHTML = "<option>Loading slots...</option>";

      const res = await fetch(`fetch_slots.php?center_id=${centerId}&date=${selectedDate}`);
      const data = await res.json();

      if (data.length === 0) {
        timeSelect.innerHTML = "<option>No slots available</option>";
        return;
      }

      timeSelect.innerHTML = "";
      data.forEach(slot => {
        const opt = document.createElement("option");
        opt.textContent = slot.slot;
        opt.value = slot.value || "";
        if (!slot.available) opt.disabled = true;
        timeSelect.appendChild(opt);
      });
    });
  });

  // === CARD VALIDATION ===
  const cardNumber = document.getElementById('card_number');
  const cardType = document.getElementById('card_type');

  cardNumber.addEventListener('input', () => {
    cardNumber.value = cardNumber.value.replace(/\D/g, '');
    let len = cardNumber.value.length;
    let type = cardType.value;
    if (type === 'Visa' && len > 16) cardNumber.value = cardNumber.value.slice(0, 16);
    if (type === 'MasterCard' && len > 16) cardNumber.value = cardNumber.value.slice(0, 16);
    if (type === 'Debit' && len > 19) cardNumber.value = cardNumber.value.slice(0, 19);
    if (type === 'Credit' && len > 16) cardNumber.value = cardNumber.value.slice(0, 16);
  });
// === EXPIRY DROPDOWNS ===
const monthSelect = document.getElementById('exp_month');
const yearSelect = document.getElementById('exp_year');
const expError = document.getElementById('exp_error');
const payButton = document.querySelector(".btn-primary");

for (let m = 1; m <= 12; m++) {
  const opt = document.createElement("option");
  opt.value = m < 10 ? `0${m}` : m;
  opt.textContent = new Date(0, m - 1).toLocaleString('default', { month: 'long' });
  monthSelect.appendChild(opt);
}

const currentYear = new Date().getFullYear();
for (let y = currentYear; y <= currentYear + 10; y++) {
  const opt = document.createElement("option");
  opt.value = y;
  opt.textContent = y;
  yearSelect.appendChild(opt);
}

// === FINAL VALIDATION ===
function validateExpiry() {
  const month = parseInt(monthSelect.value);
  const year = parseInt(yearSelect.value);
  const now = new Date();

  if (!month || !year) return;

  // Expiry date: last day of the selected month/year
  const expiry = new Date(year, month, 0, 23, 59, 59);

  if (expiry < now) {
    expError.style.display = "block";
    payButton.disabled = true;
  } else {
    expError.style.display = "none";
    payButton.disabled = false;
  }
}

monthSelect.addEventListener("change", validateExpiry);
yearSelect.addEventListener("change", validateExpiry);
</script>
</body>
</html>
