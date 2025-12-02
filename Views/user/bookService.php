<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { header("Location: /YardProProject/?route=user/login"); exit; }

$controller = new UserController();

// Validate center ID from previous page
$center_id = $_POST['center_id'] ?? null;
if (!$center_id) die("Invalid service center");

// Get center details through controller (MVC correct)
$center = $controller->getServiceCenter($center_id);
if (!$center) die("Service center not found");

// Handle booking submit
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_booking'])) {

    $data = $_POST;

    // Build expiry date MM/YY
    $data['expiry_date'] = sprintf('%02d/%02d', $data['exp_month'], $data['exp_year'] % 100);

    $result = $controller->processBooking($user_id, $center_id, $data);

    if ($result === "success") {
        header("Location: /YardProProject/?route=user/view-bookings&msg=Booking+successful");
        exit;
    } elseif ($result === "Slot Full") {
        $message = "This time slot is full. Choose another time.";
    } else {
        $message = "Something went wrong.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Book Service</title>
<link rel="stylesheet" href="/YardProProject/Static/css/user.css">
</head>
<body>

<?php include __DIR__ . '/../shared/navbar.php'; ?>

<div class="book-container">

    <h2>Book Service</h2>

    <?php if (!empty($message)): ?>
        <p class="error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
<button id="toggleBookForm" class="btn btn-primary" style="margin-bottom: 10px;">
    Show / Hide Booking Form
</button>

<div id="bookingForm" style="display: none;">

    <form method="post">

        <input type="hidden" name="center_id" value="<?= $center['center_id'] ?>">

        <label>Service</label>
        <input type="text" name="service_name" value="<?= $center['name'] ?>" readonly>

        <label>Price</label>
        <input type="number" name="price" value="<?= $center['base_price'] ?>" readonly>

        <label>Date</label>
        <input type="date" name="booking_date" id="booking_date" required>

        <label>Time</label>
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
        <input type="text" name="card_number" id="card_number" maxlength="16" required>

        <label>CVV</label>
        <input type="text" name="cvv" maxlength="3" required>

        <label>Expiry Date</label>
        <div style="display:flex; gap:10px;">
            <select name="exp_month" id="exp_month" required></select>
            <select name="exp_year" id="exp_year" required></select>
        </div>

        <button type="submit" name="confirm_booking" class="btn-primary">Pay & Book</button>

    </form>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        timeSelect.innerHTML = "<option>Loading...</option>";

        const res = await fetch(
            `/YardProProject/?route=user/fetch-slots&center_id=${centerId}&date=${selectedDate}`
        );

        const data = await res.json();
        timeSelect.innerHTML = "";

        if (data.length === 0) {
            timeSelect.innerHTML = "<option>No slots available</option>";
            return;
        }

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
});

// === EXPIRY DROPDOWNS ===
const monthSelect = document.getElementById('exp_month');
const yearSelect = document.getElementById('exp_year');

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


$(document).ready(function(){

    $("#toggleBookForm").click(function(){
        $("#bookingForm").slideToggle("fast");  
    });

});
</script>


</body>
</html>
