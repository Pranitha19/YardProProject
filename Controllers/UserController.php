<?php
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

    // Build the payload that matches the model method
    $payload = [
        'user_id'    => $user_id,
        'first_name' => trim($data['first_name']),
        'last_name'  => trim($data['last_name']),
        'phone_no'   => trim($data['phone_no']),
        'address'    => trim($data['address'])
    ];

    // Call model function (this exists)
    return $this->userModel->updateProfile($payload);
}


    public function getServiceCenters($search = "") {
        return $this->userModel->getServiceCenters($search);
    }

    public function getServiceCenter($center_id) {
        return $this->userModel->getServiceCenterById($center_id);
    }

    public function getUserBookings($user_id) {
        return $this->userModel->getBookingsByUser($user_id);
    }

    public function getBookingById($booking_id) {
        return $this->userModel->getBookingById($booking_id);
    }

    public function updateBooking($booking_id, $date, $time) {
        return $this->userModel->updateBookingDateTime($booking_id, $date, $time);
    }

   public function cancelBooking($booking_id) {
    return $this->userModel->cancelBooking($booking_id);
}

    public function isSlotTaken($center_id, $date, $time, $exclude = null) {
        return $this->userModel->isSlotTaken($center_id, $date, $time, $exclude);
    }

    public function processBooking($user_id, $center_id, $data) {

        if ($this->userModel->isSlotTaken($center_id, $data['booking_date'], $data['booking_time'])) {
            return "Slot Full";
        }

        $booking_id = $this->userModel->createBooking([
            'user_id' => $user_id,
            'center_id' => $center_id,
            'service_name' => $data['service_name'],
            'price' => $data['price'],
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time']
        ]);
        $this->userModel->createPayment([
            'booking_id' => $booking_id,
            'amount' => $data['price'],
            'card_holder' => $data['card_holder'],
            'card_number' => $data['card_number'],
            'card_type' => $data['card_type'],
            'cvv' => $data['cvv'],
            'expiry_date' => $data['expiry_date']
        ]);

        return "success";
    }
}
?>
