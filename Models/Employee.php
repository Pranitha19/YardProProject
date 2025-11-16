<?php
/**
* Employee Model- Handles Employee authentication, profile updates, and job-related queries
*/
require_once( __DIR__ . '/../config/pdo.php' );

class Employee {

    // Authenticate Employee Login

    public function login( $email, $password ) {
        global $pdo;

        $stmt = $pdo->prepare( 'SELECT * FROM employees WHERE email = ?' );
        $stmt->execute( [ $email ] );
        $employee = $stmt->fetch();

        if ( !$employee ) return false;

        if ( password_verify( $password, $employee[ 'password_hash' ] ) ) {
            return $employee;
        }

        return false;
    }

    /* --------------------------------
    Fetch employee details by ID
    --------------------------------- */

    public function getEmployeeById( $id ) {
        global $pdo;
        $stmt = $pdo->prepare( 'SELECT * FROM employees WHERE employee_id = ?' );
        $stmt->execute( [ $id ] );
        return $stmt->fetch();
    }

    // Update employee profile

    public function updateProfile( $data ) {
        global $pdo;

        $sql = "UPDATE employees 
                SET name = ?, phone_no = ?, address = ?
                WHERE employee_id = ?";

        return $pdo->prepare( $sql )->execute( [
            $data[ 'name' ],
            $data[ 'phone_no' ],
            $data[ 'address' ],
            $data[ 'employee_id' ]
        ] );
    }

    // Update password

    public function updatePassword( $employee_id, $newPass ) {
        global $pdo;

        $hash = password_hash( $newPass, PASSWORD_DEFAULT );

        return $pdo->prepare( 'UPDATE employees SET password_hash = ? WHERE employee_id = ?' )
        ->execute( [ $hash, $employee_id ] );
    }

    //Assigned bookings for employee ( viewRequest.php )

    public function getAssignedRequests( $employee_id ) {
        global $pdo;

        // Adjust table names if your bookings table differs
        $sql = "SELECT b.id AS booking_id,
        b.status,
        b.price,
        u.firstname,
        u.lastname,
        s.name AS service_name
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN service_centers s ON b.servicecenter_id = s.center_id
                WHERE b.employee_id = ?
                ORDER BY b.id DESC";

        $stmt = $pdo->prepare( $sql );
        $stmt->execute( [ $employee_id ] );
        return $stmt->fetchAll();
    }

    //Update booking status

    public function updateStatus( $booking_id, $employee_id, $status ) {
        global $pdo;

        $sql = "UPDATE bookings SET status = ?
                WHERE id = ? AND employee_id = ?";

        return $pdo->prepare( $sql )->execute( [
            $status,
            $booking_id,
            $employee_id
        ] );
    }
}