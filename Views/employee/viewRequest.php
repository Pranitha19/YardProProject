<?php
session_start();
require_once( '../../controllers/EmployeeController.php' );
if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: login.php' );
    exit();
}

// Employee must be logged in
if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: ../admin/login.php' );
    exit();
}

$controller = new EmployeeController();

// Get logged-in employee's requests
$employee_id = $_SESSION['employee_id'];
$requests = $controller->getAssignedRequests($employee_id);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>My Requests - YardPro</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='../../Static/css/styles.css' rel='stylesheet'>
</head>

<body>

    <nav class='navbar navbar-dark navbar-expand-lg' style='background:#2e7d32;
'>
        <div class='container-fluid'>
            <a class='navbar-brand fw-bold' href='viewRequest.php'>YardPro Employee</a>
            <ul class='navbar-nav ms-auto'>
                <li class='nav-item'><a class='nav-link' href='editProfile.php'>Profile</a></li>
                <li class='nav-item'><a class='nav-link text-warning' href='logout.php'>Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class='container mt-4'>
        <h3 class='text-success mb-3'>Assigned Requests</h3>

        <table class='table table-bordered table-hover'>
            <thead class='table-success'>
                <tr>
                    <th>Booking ID</th>
                    <th>User</th>
                    <th>Service Center</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $requests as $r ): ?>
                <tr>
                    <td>
                        <?= $r[ 'booking_id' ] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars( $r[ 'firstname' ].' '.$r[ ' lastname' ] ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars( $r[ 'service_name' ] ) ?>
                    </td>
                    <td>$
                        <?= htmlspecialchars( $r[ 'price' ] ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars( $r[ 'status' ] ) ?>
                    </td>
                </tr>
                <?php endforeach;
?>

                <?php if ( empty( $requests ) ): ?>
                <tr>
                    <td colspan='5' class='text-center text-muted'>No assigned requests.</td>
</tr>
<?php endif;
?>
</tbody>
</table>
</div>

</body>

</html>