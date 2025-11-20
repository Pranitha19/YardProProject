<?php
require_once( __DIR__ . '/../config/pdo.php' );

class Employee {

    /* ----------------------------------------
    EXISTING METHOD ( DO NOT TOUCH )
    ---------------------------------------- */

    // Insert new employee

    public function register( $data ) {
        global $pdo;

        // Keep your existing password hashing logic
        $hashedPassword = password_hash( $data[ 'password' ], PASSWORD_DEFAULT );

        $stmt = $pdo->prepare(
            "INSERT INTO employees (name, email, password_hash, phone_no, address)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute( [
            $data[ 'name' ],
            $data[ 'email' ],
            $hashedPassword,
            $data[ 'phone_no' ] ?? null,
            $data[ 'address' ] ?? null
        ] );
    }

    /* ----------------------------------------
    NEW METHODS ( SAFE ADDITIONS )
    ---------------------------------------- */

    // Fetch employee by ID

    public function getEmployeeById( $employee_id ) {
        global $pdo;
        $stmt = $pdo->prepare( 'SELECT * FROM employees WHERE employee_id = ?' );
        $stmt->execute( [ $employee_id ] );
        return $stmt->fetch();
    }

    // Fetch employee by Email ( for login )

    public function getEmployeeByEmail( $email ) {
        global $pdo;
        $stmt = $pdo->prepare( 'SELECT * FROM employees WHERE email = ?' );
        $stmt->execute( [ $email ] );
        return $stmt->fetch();
    }

    // Update profile details

    public function updateEmployeeProfile( $data ) {
        global $pdo;
        $stmt = $pdo->prepare(
            "UPDATE employees 
SET name = ?, phone_no = ?, address = ?
WHERE employee_id = ?"
        );

        return $stmt->execute( [
            $data[ 'name' ],
            $data[ 'phone_no' ],
            $data[ 'address' ],
            $data[ 'employee_id' ]
        ] );
    }

    // Update password

    public function updateEmployeePassword( $employee_id, $newPassword ) {
        global $pdo;

        // Secure password hashing
        $hashed = password_hash( $newPassword, PASSWORD_DEFAULT );

        $stmt = $pdo->prepare(
            'UPDATE employees SET password_hash = ? WHERE employee_id = ?'
        );

        return $stmt->execute( [ $hashed, $employee_id ] );
    }
}
?>