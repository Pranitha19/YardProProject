<?php
require_once __DIR__ . '/../Models/Employee.php' ;
require_once __DIR__ . '/../Models/Booking.php' ;

class EmployeeController {

    private $employeeModel;
    private $bookingModel;

    public function __construct() {
        $this->employeeModel = new Employee();
        $this->bookingModel  = new Booking();
    }

    public function getEmployee( $employee_id ) {
        return $this->employeeModel->getEmployeeById( $employee_id );
    }

    public function updateEmployeeProfile( $data ) {
        return $this->employeeModel->updateEmployeeProfile( $data );
    }

    public function updateEmployeePassword( $employee_id, $newPassword ) {
        return $this->employeeModel->updateEmployeePassword( $employee_id, $newPassword );
    }

    public function getAssignedRequests( $employee_id ) {
        return $this->bookingModel->getRequestsByEmployee( $employee_id );
    }

    public function updateRequestStatus( $booking_id, $employee_id, $status ) {
        return $this->bookingModel->updateBookingStatus( $booking_id, $employee_id, $status );
    }
}