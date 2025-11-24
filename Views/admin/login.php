<?php
session_start();
require_once '../../config/pdo.php';
require_once '../../controllers/AdminController.php';
require_once '../../helpers/flash.php';

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    //Try Admin login from DB (admins table)
    $adminRow = $controller->login($email, $password);

    if ($adminRow) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email']     = $adminRow['email'];
        $_SESSION['admin_name']      = $adminRow['name'];

    setFlash('success', 'Admin logged in successfully!');
    header('Location: home.php'); // Admin dashboard
        exit();
    }

    // If not admin, checks Employee login (existing logic) 
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE email = ?');
    $stmt->execute([$email]);
    $employee = $stmt->fetch();

    if ($employee && password_verify($password, $employee['password_hash'])) {
        $_SESSION['employee_logged_in'] = true;
        $_SESSION['employee_id']        = $employee['employee_id'];
        $_SESSION['employee_name']      = $employee['name'];

    setFlash('success', 'Employee logged in successfully!');
    header('Location: ../employee/home.php'); // Employee dashboard
exit();

    }

    //If both fail gives error
    setFlash('danger', 'Invalid email or password!');

}
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>Admin Login</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light d-flex justify-content-center align-items-center vh-100'>
<div class = 'card p-4 shadow' style = 'width: 22rem;'>
    <?php showFlash(); ?>
<h4 class = 'text-center text-success mb-3'>Admin & Employee Login</h4>
<?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>
<form method = 'POST'>
<input type = 'email' name = 'email' class = 'form-control mb-3' placeholder = 'Email' required>
<input type = 'password' name = 'password' class = 'form-control mb-3' placeholder = 'Password' required>
<button class = 'btn btn-success w-100'>Login</button>
</form>
</div>
<script>
setTimeout(() => {
    let msg = document.querySelector('.flash-message');
    if (msg) {
        msg.style.transition = "opacity 0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 5000);
</script>
</body>
</html>