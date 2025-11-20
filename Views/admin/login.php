<?php
session_start();
require_once( '../../config/pdo.php' );
// need PDO for employee check
require_once( '../../controllers/AdminController.php' );

$controller = new AdminController();

$ADMIN_EMAIL = 'admin@yardpro.com';
$ADMIN_PASSWORD = 'Admin@123';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $email = trim( $_POST[ 'email' ] );
    $password = trim( $_POST[ 'password' ] );

    //it will check for admin login

    if ( $email === $ADMIN_EMAIL && $password === $ADMIN_PASSWORD ) {

        $_SESSION[ 'admin_logged_in' ] = true;
        $_SESSION[ 'admin_email' ] = $email;

        header( 'Location: home.php' );
        // admin dashboard
        exit();
    }

    //️it will check for employee login ( DATABASE )
    $stmt = $pdo->prepare( 'SELECT * FROM employees WHERE email = ?' );
    $stmt->execute( [ $email ] );
    $employee = $stmt->fetch();

    if ( $employee && password_verify( $password, $employee[ 'password_hash' ] ) ) {

        $_SESSION[ 'employee_logged_in' ] = true;
        $_SESSION[ 'employee_id' ] = $employee[ 'employee_id' ];
        $_SESSION[ 'employee_name' ] = $employee[ 'name' ];

        header( 'Location: ../employee/home.php' );
        // employee dashboard
        exit();
    }

    //If admin or employee login fails
    $error = 'Invalid email or password!';
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
<h4 class = 'text-center text-success mb-3'>Admin & Employee Login</h4>
<?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>
<form method = 'POST'>
<input type = 'email' name = 'email' class = 'form-control mb-3' placeholder = 'Email' required>
<input type = 'password' name = 'password' class = 'form-control mb-3' placeholder = 'Password' required>
<button class = 'btn btn-success w-100'>Login</button>
</form>
</div>
</body>

</html>