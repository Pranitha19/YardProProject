<?php
session_start();
require_once( '../Controllers/AuthController.php' );

$auth = new AuthController();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $email = $_POST[ 'email' ];
    $password = $_POST[ 'password' ];

    $result = $auth->login( $email, $password );

    if ( $result === false ) {
        $error = 'Invalid email or password.';
    } else {

        $_SESSION[ 'role' ] = $result[ 'role' ];
        $_SESSION[ 'name' ] = $result[ 'name' ];
        $_SESSION[ 'id' ]   = $result[ 'id' ];

        switch ( $result[ 'role' ] ) {

            case 'admin':
            header( 'Location: admin/home.php' );
            exit();

            case 'employee':
            header( 'Location: employee/home.php' );
            exit();

            case 'user':
            header( 'Location: user/home.php' );
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>YardPro Login</title>

<link rel = 'stylesheet' href = '../Static/css/auth.css'>
</head>

<body>

<div class = 'auth-card'>

<h2>YardPro Login</h2>

<?php if ( !empty( $error ) ) : ?>
<!-- FIXED PHP syntax here -->
<p class = 'error'>
< ?= htmlspecialchars( $error ) ?>
</p>
<?php endif;
?>

<!-- Form posts back to same file -->
<form method = 'POST' action = ''>

<label><b>Email</b></label>
<input type = 'email' name = 'email' placeholder = 'Email address' required>

<label><b>Password</b></label>
<input type = 'password' name = 'password' placeholder = 'Password' required>

<button type = 'submit' class = 'btn-primary'>Log in</button>
</form>

<a href = 'register.php' class = 'link'>Don’t have an account? Register</a>
</div>

</body>

</html>