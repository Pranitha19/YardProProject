<?php
require_once( __DIR__ . '/../Models/Employee.php' );

class UserController {
    private $employeeModel;

    public function __construct() {
        $this->employeeModel = new Employee();
    }

    public function employeeLogin( $email, $password ) {
        return $this->employeeModel->login( $email, $password );
    }

    public function getEmployee( $id ) {
        return $this->employeeModel->getEmployeeById( $id );
    }

    public function updateEmployeeProfile( $data ) {
        return $this->employeeModel->updateProfile( $data );
    }

    public function updateEmployeePassword( $id, $newPassword ) {
        return $this->employeeModel->updatePassword( $id, $newPassword );
    }

    public function getAssignedRequests( $employee_id ) {
        return $this->employeeModel->getAssignedRequests( $employee_id );
    }

    public function updateRequestStatus( $booking_id, $employee_id, $status ) {
        return $this->employeeModel->updateStatus( $booking_id, $employee_id, $status );
    }
}