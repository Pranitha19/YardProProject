<?php
session_start();
require_once '../../controllers/EmployeeController.php';

if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: ../admin/login.php' );
    exit();
}

$controller = new EmployeeController();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $controller->updateRequestStatus(
        $_POST[ 'booking_id' ],
        $_SESSION[ 'employee_id' ],
        $_POST[ 'status' ]
    );

    header( 'Location: home.php' );
    exit();
}