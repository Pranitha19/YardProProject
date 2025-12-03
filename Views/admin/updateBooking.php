<?php

if (!isset($_SESSION['admin_id'])) {
    header('Location: /YardProProject/?route=admin/login');
    exit();
}

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id  = $_POST['booking_id'];
    $employee_id = $_POST['employee_id'] ?: null;
    $status      = $_POST['status'];

    $controller->assignEmployee($booking_id, $employee_id);
    $controller->updateBookingStatus($booking_id, $status);

    setFlash('success', "Booking #$booking_id updated successfully!");
    header('Location: /YardProProject/?route=admin/view-bookings');
    exit();
}