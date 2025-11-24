<?php
require_once __DIR__ . '/../config/pdo.php';

class Admin
{
    // Find admin by email
    public function getByEmail($email)
    {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Login: return admin row on success, false on failure
    public function login($email, $password)
    {
        $admin = $this->getByEmail($email);
        if (!$admin) {
            return false;
        }

        if (password_verify($password, $admin['password_hash'])) {
            return $admin; // return full row so we can store name/email
        }

        return false;
    }
}