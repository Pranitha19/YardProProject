<?php
//This file allows employees to update job progress.
session_start();
require_once __DIR__ . '/../../config/pdo.php';
/*Reads booking ID and new status ( like “Completed” or “InProgress” ).
Updates that bookings status in the database and
redirects back to the list after updating. */
if ( !isset( $_SESSION[ 'role' ] ) || $_SESSION[ 'role' ] !== 'employee' ) {
    header( 'Location: login.php' );
    exit;
}

if ( isset( $_POST[ 'booking_id' ] ) && isset( $_POST[ 'status' ] ) ) {
    $booking_id = $_POST[ 'booking_id' ];
    $status = $_POST[ 'status' ];
    $emp_id = $_SESSION[ 'user_id' ];
    $stmt = $pdo->prepare( 'UPDATE bookings SET status=? WHERE booking_id=? AND employee_id=?' );
    $stmt->execute( [ $status, $booking_id, $emp_id ] );

    header( 'Location: viewrequests.php' );
    exit;
}
?>