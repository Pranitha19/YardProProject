<?php
/* Admin Controller. Connects views to models */
require_once( __DIR__ . '/../models/Admin.php' );
require_once( __DIR__ . '/../models/ServiceCenter.php' );

class AdminController {
    private $adminModel;
    private $serviceCenterModel;

    public function __construct() {
        $this->adminModel = new Admin();
        $this->serviceCenterModel = new ServiceCenter();
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
}
?>