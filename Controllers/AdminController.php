<?php
/* Admin Controller. Connects views to models */
require_once( __DIR__ . '/../Models/Admin.php' );
require_once( __DIR__ . '/../Models/ServiceCenter.php' );
require_once( __DIR__ . '/../Models/Employee.php' );

class AdminController {
    private $adminModel;
    private $serviceCenterModel;
    private $employeeModel;

    public function __construct() {
        $this->adminModel = new Admin();
        $this->serviceCenterModel = new ServiceCenter();
        $this->employeeModel = new Employee();
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
}
?>