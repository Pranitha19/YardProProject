<?php
session_start();
require_once __DIR__ . '/../../config/pdo.php';
if ( !isset( $_SESSION[ 'role' ] ) || $_SESSION[ 'role' ] !== 'employee' ) {
    header( 'Location: login.php' );
    exit;
}
//Uses the logged-in employee’s ID to get their current info from the database
$emp_id = $_SESSION[ 'user_id' ];

// Fetch employee details
$stmt = $pdo->prepare( 'SELECT * FROM employees WHERE employee_id=?' );
$stmt->execute( [ $emp_id ] );
$emp = $stmt->fetch();
?>
<!DOCTYPE html>
<html>

<head>
<meta charset = 'utf-8'>
<title>Edit Profile</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'container py-5'>
<h3>Edit Profile</h3>
<!-- Allows updating name, phone, address, and optionally a new password. -->
<form method = 'post' action = ''>
<div class = 'mb-3'>
<label>Name</label>
<input type = 'text' name = 'name' class = 'form-control' value = "<?=htmlspecialchars($emp['name'])?>" required>
</div>
<div class = 'mb-3'>
<label>Email ( cannot change )</label>
<input type = 'email' class = 'form-control' value = "<?=htmlspecialchars($emp['email'])?>" readonly>
</div>
<div class = 'mb-3'>
<label>Phone</label>
<input type = 'text' name = 'phone' class = 'form-control' value = "<?=htmlspecialchars($emp['phone_no'])?>">
</div>
<div class = 'mb-3'>
<label>Address</label>
<input type = 'text' name = 'address' class = 'form-control' value = "<?=htmlspecialchars($emp['address'])?>">
</div>
<div class = 'mb-3'>
<label>New Password ( optional )</label>
<input type = 'password' name = 'new_password' class = 'form-control'>
</div>
<button type = 'submit' name = 'update' class = 'btn btn-success'>Save Changes</button>
<a href = 'viewrequests.php' class = 'btn btn-secondary'>Cancel</a>
</form>
</body>

</html>

<?php
//Reads the submitted form values.
if ( isset( $_POST[ 'update' ] ) ) {
    $name = trim( $_POST[ 'name' ] );
    $phone = trim( $_POST[ 'phone' ] );
    $address = trim( $_POST[ 'address' ] );
    $new_password = trim( $_POST[ 'new_password' ] );
    //If a new password is entered, the new password is hashed using password_hash() and saved.
    if ( $new_password !== '' ) {
        $hash = password_hash( $new_password, PASSWORD_DEFAULT );
        $stmt = $pdo->prepare( 'UPDATE employees SET name=?, phone_no=?, address=?, password_hash=? WHERE employee_id=?' );
        $stmt->execute( [ $name, $phone, $address, $hash, $emp_id ] );
    }
    //If not, Updates only basic info.
    else {
        $stmt = $pdo->prepare( 'UPDATE employees SET name=?, phone_no=?, address=? WHERE employee_id=?' );
        $stmt->execute( [ $name, $phone, $address, $emp_id ] );
    }
    $_SESSION[ 'user_name' ] = $name;
    //Displays a success message.
    echo "<p class='text-success mt-3'>Profile updated successfully!</p>";
}
?>