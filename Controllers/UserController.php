<?php
require_once __DIR__ . '/../config/pdo.php';
require_once __DIR__ . '/../Models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function getUserDetails($user_id) {
        return $this->userModel->findById($user_id);
    }

    public function updateProfile($user_id, $data) {
        $first = trim($data['first_name']);
        $last  = trim($data['last_name']);
        $phone = trim($data['phone_no']);
        $addr  = trim($data['address']);

        if ($first === '' || $last === '') {
            header("Location: ../../Views/user/editProfile.php?err=First and last name required");
            exit;
        }

        $this->userModel->update($user_id, $first, $last, $phone, $addr);
        header("Location: ../../Views/user/editProfile.php?msg=Profile updated successfully");
        exit;
    }

    public function getServiceCenters($pdo, $search = '') {
        if ($search) {
            $stmt = $pdo->prepare("SELECT * FROM service_centers WHERE name LIKE :search ORDER BY name ASC");
            $stmt->execute([':search' => "%$search%"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM service_centers ORDER BY name ASC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserBookings($user_id) {
    return $this->userModel->getBookingsByUser($user_id);
}


    public function cancelBooking($booking_id) {
    global $pdo;

    // Fetch booking first
    $stmt = $pdo->prepare("SELECT booking_date, booking_time, status FROM bookings WHERE booking_id = :bid");
    $stmt->execute([':bid' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        header("Location: ../../Views/user/viewBookings.php?err=Booking not found");
        exit;
    }

    // Calculate time difference
    $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['booking_time']);
    $now = time();
    $hoursDiff = ($bookingDateTime - $now) / 3600;

    if ($booking['status'] !== 'Requested') {
        header("Location: ../../Views/user/viewBookings.php?err=Cannot cancel — booking already processed");
        exit;
    }

    if ($hoursDiff <= 24) {
        header("Location: ../../Views/user/viewBookings.php?toast=late_cancel");
        exit;
    }

    // Update status to Cancelled
    $update = $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE booking_id = :bid");
    $update->execute([':bid' => $booking_id]);

    header("Location: /YardProProject/Views/user/viewBookings.php?toast=cancel_success");

    exit;
    
}
public function editBooking($booking_id) {
    require_once __DIR__ . '/../config/pdo.php';
    $model = new User();

    $booking = $model->getBookingById($booking_id);
    if (!$booking) {
        header("Location: /YardProProject/Views/user/viewBookings.php?err=Booking not found");
        exit;
    }

    // Prevent editing within 24 hours
    $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['booking_time']);
    if (($bookingDateTime - time()) / 3600 < 24) {
        header("Location: /YardProProject/Views/user/viewBookings.php?err=Cannot edit within 24 hours of booking");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_date = $_POST['booking_date'];
        $new_time = $_POST['booking_time'];

        // Check slot conflict
        if ($model->isSlotTaken($booking['center_id'], $new_date, $new_time, $booking_id)) {
            $error = "This time slot is already booked.";
            include __DIR__ . '/../Views/user/editBooking.php';
            return;
        }

        $model->updateBookingDateTime($booking_id, $new_date, $new_time);
        header("Location: /YardProProject/Views/user/viewBookings.php?msg=Booking updated successfully");
        exit;
    }

    // ✅ If not POST → load the edit form
    include __DIR__ . '/../Views/user/editBooking.php';
}


}


if (isset($_GET['action'])) {
    require_once __DIR__ . '/../models/User.php';
    $controller = new UserController();

    if ($_GET['action'] === 'editBooking' && isset($_GET['booking_id'])) {
        $controller->editBooking($_GET['booking_id']);
    }

    if ($_GET['action'] === 'cancelBooking' && isset($_GET['booking_id'])) {
        $controller->cancelBooking($_GET['booking_id']);
    }
}


