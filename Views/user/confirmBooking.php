<?php
require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../config/pdo.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_service'])) {
    $center_id = $_POST['center_id'];
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $date = $_POST['service_date'];
    $timing = $_POST['timing'];

    $card_holder = $_POST['card_holder'];
    $card_type = $_POST['card_type'];
    $card_number = $_POST['card_number'];
    $cvv = $_POST['cvv'];
    $expiry_date = $_POST['expiry_date'];

    // Insert booking
    $bookStmt = $pdo->prepare("
        INSERT INTO bookings (user_id, center_id, service_name, booking_date, timing, price)
        VALUES (:uid, :cid, :sname, :bdate, :time, :price)
    ");
    $bookStmt->execute([
        ':uid' => $user_id,
        ':cid' => $center_id,
        ':sname' => $service_name,
        ':bdate' => $date,
        ':time' => $timing,
        ':price' => $price
    ]);

    $booking_id = $pdo->lastInsertId();

    // Insert payment
    $payStmt = $pdo->prepare("
        INSERT INTO payments (booking_id, amount, card_type, card_holder, card_number, cvv, expiry_date)
        VALUES (:bid, :amt, :ctype, :cholder, :cnum, :cvv, :exp)
    ");
    $payStmt->execute([
        ':bid' => $booking_id,
        ':amt' => $price,
        ':ctype' => $card_type,
        ':cholder' => $card_holder,
        ':cnum' => $card_number,
        ':cvv' => $cvv,
        ':exp' => $expiry_date
    ]);

    header('Location: viewBookings.php?msg=Booking successful');
    exit;
} else {
    header('Location: home.php');
    exit;
}
