<?php

if (!isset($_SESSION['employee_id'])) {
    header('Location: /YardProProject/?route=employee/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EmployeeController();

    $controller->updateRequestStatus(
        $_POST['booking_id'],
        $_SESSION['employee_id'],
        $_POST['status']
    );

    setFlash('success', 'Booking status updated successfully!');
    header('Location: /YardProProject/?route=employee/home');
    exit();
}
