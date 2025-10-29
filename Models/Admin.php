<?php

class Admin {
    private $email = 'admin@yardpro.com';
    private $password = 'Admin@123';

    public function login( $email, $password ) {
        return ( $email === $this->email && $password === $this->password );
    }
}
?>