<?php
//Admin Controller. Connects views to models

require_once __DIR__ . '/../Models/Admin.php' ;
require_once __DIR__ . '/../Models/ServiceCenter.php' ;
require_once __DIR__ . '/../Models/Employee.php' ;
require_once __DIR__ . '/../Models/Booking.php' ;

class AdminController {
    private $adminModel;
    private $serviceCenterModel;
    private $employeeModel;
    private $bookingModel;

    public function __construct() {
        $this->adminModel = new Admin();
        $this->serviceCenterModel = new ServiceCenter();
        $this->employeeModel = new Employee();
        $this->bookingModel = new Booking();
    }

    public function login( $email, $password ) {
        return $this->adminModel->login( $email, $password );
    }

    // Service center CRUD

    public function addServiceCenter( $data ) {
        return $this->serviceCenterModel->addServiceCenter( $data );
    }

    public function getAllCenters() {
        return $this->serviceCenterModel->getAllCenters();
    }

    public function updateServiceCenter( $data ) {
        return $this->serviceCenterModel->updateServiceCenter( $data );
    }

    public function deleteServiceCenter( $center_id ) {
        return $this->serviceCenterModel->deleteServiceCenter( $center_id );
    }

    public function registerEmployee( $data ) {
        return $this->employeeModel->register( $data );
    }

    // Admin — Assign Employee

    public function assignEmployee( $booking_id, $employee_id ) {
        return $this->bookingModel->adminAssignEmployee( $booking_id, $employee_id );
    }

    // Admin — Update Booking Status

    public function updateBookingStatus( $booking_id, $status ) {
        return $this->bookingModel->adminUpdateStatus( $booking_id, $status );
    }

    public function getAllEmployees() {
        return $this->employeeModel->getAllEmployees();
    }

}