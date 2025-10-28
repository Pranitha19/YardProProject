<?php
/**
* ServiceCenter Model ( FINAL )
* CRUD for 'service_centers' table
*/
require_once( __DIR__ . '/../config/pdo.php' );

class ServiceCenter {

    // CREATE

    public function addServiceCenter( $data ) {
        global $pdo;
$stmt = $pdo->prepare( "INSERT INTO service_centers (name, email, phone_no, description, address, timings_note, base_price, image_url)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute( [
            $data[ 'name' ],
            $data[ 'email' ] ?? null,
            $data[ 'phone_no' ] ?? null,
            $data[ 'description' ] ?? null,
            $data[ 'address' ],
            $data[ 'timings_note' ] ?? null,
            // allow empty to become NULL or '0.00'
            ( $data[ 'base_price' ] === '' ? null : $data[ 'base_price' ] ),
            $data[ 'image_url' ] ?? null
        ] );
    }

    // READ

    public function getAllCenters() {
        global $pdo;
        // include created_at & image_url for downstream use if needed
        $stmt = $pdo->query(
            "SELECT center_id, name, email, phone_no, description, address,
                    timings_note, base_price, image_url, created_at
             FROM service_centers
             ORDER BY center_id DESC"
        );
        return $stmt->fetchAll();
    }

    // UPDATE

    public function updateServiceCenter( $data ) {
        global $pdo;
        $stmt = $pdo->prepare(
            "UPDATE service_centers
             SET name = ?, email = ?, phone_no = ?, description = ?, address = ?,
                 timings_note = ?, base_price = ?, image_url = ?
             WHERE center_id = ?"
        );
        return $stmt->execute( [
            $data[ 'name' ] ?? null,
            $data[ 'email' ] ?? null,
            $data[ 'phone_no' ] ?? null,
            $data[ 'description' ] ?? null,
            $data[ 'address' ] ?? null,
            $data[ 'timings_note' ] ?? null,
            ( $data[ 'base_price' ] === '' ? null : $data[ 'base_price' ] ),
            $data[ 'image_url' ] ?? null,
            $data[ 'center_id' ]
        ] );
    }

    // DELETE

    public function deleteServiceCenter( $center_id ) {
        global $pdo;
        $stmt = $pdo->prepare( 'DELETE FROM service_centers WHERE center_id = ?' );
        return $stmt->execute( [ $center_id ] );
    }
}
?>