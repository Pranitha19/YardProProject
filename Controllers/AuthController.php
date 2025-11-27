<?php
require_once __DIR__ . '/../config/pdo.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register($data) {
        $first = trim($data['first_name']);
        $last  = trim($data['last_name']);
        $email = trim($data['email']);
        $pass  = $data['password'];
        $phone = trim($data['phone_no'] ?? '');
        $addr  = trim($data['address'] ?? '');

        if ($first === '' || $last === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
            header("Location: ../Views/user/register.php?err=Invalid input");
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            header("Location: ../Views/user/register.php?err=Email already registered");
            exit;
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $this->userModel->createUser($first, $last, $email, $hash, $phone, $addr);

        header("Location: ../Views/user/login.php?msg=Registered successfully. Please log in.");
        exit;
    }

    public function login($data) {
        session_start();
        $email = trim($data['email']);
        $pass  = $data['password'];

        // hardcoded admin
        // $admin_email = 'admin@yardpro.com';
        // $admin_hashed_password = '$2y$10$O4tueB9oEJG6vXL0AFZC8uZp05OGa0LbA.A0GEy0qZpN9K8Cmy25u'; // Admin@123

        // if ($email === $admin_email && password_verify($pass, $admin_hashed_password)) {
        //     $_SESSION['role'] = 'admin';
        //     header('Location: ../Views/admin/home.php');
        //     exit;
        // }

        $user = $this->userModel->getUserByEmail($email);
        if (!$user || !password_verify($pass, $user['password_hash'])) {
            header("Location: ../Views/user/login.php?err=Invalid credentials");
            exit;
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role'] = 'user';

        header("Location: ../Views/user/home.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $action = $_GET['action'] ?? '';

    if ($action === 'register') $auth->register($_POST);
    if ($action === 'login') $auth->login($_POST);
}
