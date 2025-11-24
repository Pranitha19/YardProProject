<?php
session_start();
require_once '../../controllers/EmployeeController.php';
require_once '../../helpers/flash.php';


if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: ../admin/login.php' );
    exit();
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $controller = new EmployeeController();

    $controller->updateRequestStatus(
        $_POST[ 'booking_id' ],
        $_SESSION[ 'employee_id' ],
        $_POST[ 'status' ]
    );

    setFlash('success', 'Status updated successfully!');
    header('Location: home.php');
    exit();
    // PRG redirect
}