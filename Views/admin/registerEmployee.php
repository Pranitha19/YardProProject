<?php
/**
* Register Employee Page
* Inserts a new employee record ( uses password hashing )
*/
session_start();
require_once( '../../Controllers/AdminController.php' );
$admin = new AdminController();

// Handle form submission
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    if ( $admin->registerEmployee( $_POST ) ) {
        $msg = 'Employee registered successfully!';
    } else {
        $error = 'Error registering employee!';
    }
}
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>Register Employee</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light'>
<div class = 'container mt-5'>
<h3 class = 'text-success mb-4'>Register New Employee</h3>
<?php if ( !empty( $msg ) ) echo "<div class='alert alert-success'>$msg</div>";
?>
<?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>

<form method = 'POST'>
<div class = 'mb-3'><input type = 'text' name = 'name' class = 'form-control' placeholder = 'Full Name' required>
</div>
<div class = 'mb-3'><input type = 'email' name = 'email' class = 'form-control' placeholder = 'Email' required></div>
<div class = 'mb-3'><input type = 'password' name = 'password' class = 'form-control' placeholder = 'Password'
required></div>
<div class = 'mb-3'><input type = 'text' name = 'phone_no' class = 'form-control' placeholder = 'Phone' required>
</div>
<div class = 'mb-3'><textarea name = 'address' class = 'form-control' placeholder = 'Address' required></textarea>
</div>
<button class = 'btn btn-success'>Register</button>
<a href = 'home.php' class = 'btn btn-secondary'>Back</a>
</form>
</div>
</body>

</html>