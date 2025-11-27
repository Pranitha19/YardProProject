<?php
session_start();
require_once '../../controllers/AdminController.php';
require_once '../../helpers/flash.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
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
    header('Location: viewAllBookings.php');
    exit();
}