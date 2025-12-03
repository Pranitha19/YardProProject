<?php
require_once( __DIR__ . '/../config/pdo.php' );

class Booking {

    public function getRequestsByEmployee( $employee_id ) {
        global $pdo;

        $sql = "SELECT b.*, s.name AS center_name
                FROM bookings b
                JOIN service_centers s ON b.center_id = s.center_id
                WHERE b.employee_id = ?
                ORDER BY b.booking_id DESC";

        $stmt = $pdo->prepare( $sql );
        $stmt->execute( [ $employee_id ] );
        return $stmt->fetchAll();
    }

    public function updateBookingStatus( $booking_id, $employee_id, $status ) {
        global $pdo;

        $sql = "UPDATE bookings
                SET status = ?
                WHERE booking_id = ? AND employee_id = ?";

        $stmt = $pdo->prepare( $sql );
        return $stmt->execute( [ $status, $booking_id, $employee_id ] );
    }

    //Admin assign employee to booking

    public function adminAssignEmployee( $booking_id, $employee_id ) {
        global $pdo;

        $sql = 'UPDATE bookings SET employee_id = ? WHERE booking_id = ?';
        $stmt = $pdo->prepare( $sql );

        return $stmt->execute( [ $employee_id, $booking_id ] );
    }

    public function adminUpdateStatus( $booking_id, $status ) {
        global $pdo;

        $sql = 'UPDATE bookings SET status = ? WHERE booking_id = ?';
        $stmt = $pdo->prepare( $sql );

        return $stmt->execute( [ $status, $booking_id ] );
    }
}