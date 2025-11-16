<?php
session_start();
require_once( '../../Controllers/UserController.php' );

$controller = new UserController();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $email    = $_POST[ 'email' ];
    $password = $_POST[ 'password' ];

    $emp = $controller->employeeLogin( $email, $password );

    if ( $emp ) {
        $_SESSION[ 'employee_logged_in' ] = true;
        $_SESSION[ 'employee_id' ] = $emp[ 'employee_id' ];
        $_SESSION[ 'employee_name' ] = $emp[ 'name' ];

        header( 'Location: viewRequest.php' );
        exit();
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
<meta charset = 'UTF-8'>
<title>Employee Login - YardPro</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
<link href = '../../Static/css/styles.css' rel = 'stylesheet'>
</head>

<body class = 'auth-bg'>

<div class = 'container d-flex justify-content-center align-items-center vh-100'>
<div class = 'card p-4 shadow auth-card'>
<h3 class = 'text-center text-success mb-3'>Employee Login</h3>

<?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>

<form method = 'POST'>
<input name = 'email' type = 'email' class = 'form-control mb-3' placeholder = 'Email' required>
<input name = 'password' type = 'password' class = 'form-control mb-3' placeholder = 'Password' required>
<button class = 'btn btn-success w-100'>Login</button>
</form>
</div>
</div>

</body>

</html>