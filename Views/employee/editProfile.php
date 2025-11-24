<?php
session_start();
require_once '../../controllers/EmployeeController.php';
require_once '../../helpers/flash.php';

if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: ../admin/login.php' );
    exit();
}

$controller = new EmployeeController();
$employee = $controller->getEmployee( $_SESSION[ 'employee_id' ] );

if ( isset( $_POST[ 'save' ] ) ) {
    $data = [
        'employee_id' => $_SESSION[ 'employee_id' ],
        'name'        => $_POST[ 'name' ],
        'phone_no'    => $_POST[ 'phone_no' ],
        'address'     => $_POST[ 'address' ]
    ];

    if ( $controller->updateEmployeeProfile( $data ) ) {
        setFlash('success', 'Profile updated!');
        $employee = $controller->getEmployee( $_SESSION[ 'employee_id' ] );
    } else {
        setFlash('danger', 'Failed to update.');

    }
}

if ( isset( $_POST[ 'change_password' ] ) ) {
    if ( $controller->updateEmployeePassword( $_SESSION[ 'employee_id' ], $_POST[ 'new_password' ] ) ) {
        setFlash('success', 'Password changed successfully!');

    } else {
    setFlash('danger', 'Password change failed.');
    header("Location: editProfile.php");
    exit();
}
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>Edit Profile - YardPro</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>

<body>

    <nav class='navbar navbar-dark navbar-expand-lg' style='background:#2e7d32;'>
        <div class='container-fluid'>
            <a class='navbar-brand fw-bold' href='home.php'>YardPro Employee</a>
            <ul class='navbar-nav ms-auto'>
                <li class='nav-item'><a class='nav-link text-warning' href='logout.php'>Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class='container mt-4'>

        <h3 class='text-success mb-3'>Edit Profile</h3>
        <?php showFlash(); ?>


        <?php if ( !empty( $msg ) ) echo "<div class='alert alert-success'>$msg</div>";
?>
        <?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>

        <form method='POST'>
            <input name='name' class='form-control mb-3' value="<?= htmlspecialchars($employee['name']) ?>" required>

            <input name='phone_no' class='form-control mb-3' value="<?= htmlspecialchars($employee['phone_no']) ?>">

            <textarea name="address" class="form-control mb-3">
<?= htmlspecialchars($employee['address']) ?>
</textarea>


            <button name='save' class='btn btn-success'>Save Changes</button>
        </form>

        <hr>

        <h4>Change Password</h4>

        <?php if ( !empty( $passmsg ) ) echo "<div class='alert alert-success'>$passmsg</div>";
?>
        <?php if ( !empty( $passerror ) ) echo "<div class='alert alert-danger'>$passerror</div>";
?>

        <form method='POST'>
            <input name='new_password' type='password' class='form-control mb-3' placeholder='New Password' required>
            <button name='change_password' class='btn btn-warning'>Update Password</button>
        </form>

    </div>

</body>

</html>