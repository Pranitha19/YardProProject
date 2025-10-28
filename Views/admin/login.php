<?php
session_start();
require_once( '../../controllers/AdminController.php' );

$controller = new AdminController();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $email = $_POST[ 'email' ];
    $password = $_POST[ 'password' ];

    if ( $controller->login( $email, $password ) ) {
        $_SESSION[ 'admin_logged_in' ] = true;
        $_SESSION[ 'admin_email' ] = $email;
        header( 'Location: home.php' );
        exit();
    } else {
        $error = 'Invalid email or password!';
    }
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
<h4 class = 'text-center text-success mb-3'>Admin Login</h4>
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