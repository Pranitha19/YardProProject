<?php

/*If admin enters admin@yardpro.com and Admin@123, they'll be logged in and
redirected to the dashboard.Otherwise, they'll see the error on the same page. */

/*This starts a session. We need it because we'll store data
like $_SESSION[ 'role' ] = 'admin' after successful login. */
session_start();

/* If the admin has already logged in before, we skip the login form and take them straight to the dashboard.
header( 'Location: ...' ) redirects the page.*/

if ( isset( $_SESSION[ 'role' ] ) && $_SESSION[ 'role' ] === 'admin' ) {
    header( 'Location: admindashboard.php' );
    exit;
}

/* Hardcoded admin credentials ( we can change anytime ) and
A hashed version of the password using password_hash().*/

$admin_email = 'admin@yardpro.com';
$admin_password_hash = password_hash( 'Admin@123', PASSWORD_DEFAULT );

/* When form is submitted, This part runs when you press the Login button.
We read the email and password that the admin entered. */

if ( isset( $_POST[ 'login' ] ) ) {
    $email = trim( $_POST[ 'email' ] );
    $password = trim( $_POST[ 'password' ] );

    /* Verify admin credentials. First, check if the entered email matches the hardcoded one.
    Then, will use password_verify() to compare the entered password with the hashed version.
    If both matches then login success and creates a new secure session ID.
    Stores role and name in session,Redirect to admindashboard.php. Otherwise, shows an error message. */
//
    if ( $email === $admin_email && password_verify( $password, $admin_password_hash ) ) {
        session_regenerate_id( true );
/*session_regenerate_id(true) → gives a new session ID to prevent hacking (called session fixation protection).
Sets session variables: role = 'admin', user_name = 'Admin'. Redirects to dashboard. */

        $_SESSION[ 'role' ] = 'admin';
        $_SESSION[ 'user_name' ] = 'Admin';
        header( 'Location: admindashboard.php' );
        exit;
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Admin Login</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>

<body class='container py-5'>
    <h3>Admin Login</h3>

    <?php if ( isset( $error ) ): ?>
    <p class='text-danger'>
        < ?=$error ?>
    </p>
    <?php endif;
?>

    <form method='post' action='' class='w-50'>
        <div class='mb-3'>
            <label>Email</label>
            <input type='email' name='email' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Password</label>
            <input type='password' name='password' class='form-control' required minlength='6'>
        </div>
        <button type='submit' name='login' class='btn btn-primary'>Login</button>
</form>
</body>

</html>