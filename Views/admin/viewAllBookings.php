<?php
session_start();
require_once( '../../controllers/AdminController.php' );
$admin = new AdminController();

$employees = $admin->getAllEmployees();
// Admin security check
if ( !isset( $_SESSION[ 'admin_logged_in' ] ) ) {
    header( 'Location: login.php' );
    exit();
}

require_once '../../config/pdo.php' ;

$sql = "SELECT 
            b.booking_id,
            b.employee_id,
            u.first_name,
            u.last_name,
            s.name AS center_name,
            b.service_name,
            b.price,
            b.status,
            b.scheduled_for,
            b.created_at
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN service_centers s ON b.center_id = s.center_id
        ORDER BY b.booking_id DESC";

$stmt = $pdo->query( $sql );
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <title>All Bookings</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>

<body class='bg-light'>
    <div class='container mt-5'>
        <h3 class='text-success mb-4'>All Bookings</h3>
        <?php if ( !empty( $_SESSION[ 'success_msg' ] ) ): ?>
        <div class='alert alert-success'>
            <?= $_SESSION[ 'success_msg' ];
?>
        </div>
        <!-- clear flash -->
        <?php unset( $_SESSION[ 'success_msg' ] );
?>
        <?php endif;
?>

        <table class='table table-bordered table-hover'>
            <thead class='table-success'>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Service Center</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Scheduled For</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
            <tbody>
                <?php foreach ( $bookings as $b ): ?>
                <tr>
                    <td><?php echo $b[ 'booking_id' ];
?></td>
                    <td><?php echo htmlspecialchars( $b[ 'firstname' ] . ' ' . $b[ 'lastname' ] );
?></td>
                    <td><?php echo htmlspecialchars( $b[ 'center_name' ] );
?></td>
                    <td><?php echo htmlspecialchars( $b[ 'service_name' ] );
?></td>
                    <td>$<?php echo $b[ 'price' ];
?></td>
                    <td><?php echo htmlspecialchars( $b[ 'status' ] );
?></td>
                    <td><?php echo $b[ 'scheduled_for' ];
?></td>
                    <td><?php echo $b[ 'created_at' ];
?></td>

                    <!-- ACTION COLUMN -->
                    <td>
                        <form method='POST' action='updateBooking.php'>
                            <input type='hidden' name='booking_id' value="<?php echo $b['booking_id']; ?>">

                            <!-- Employee dropdown -->
                            <select name='employee_id' class='form-select form-select-sm mb-2'>
                                <option value='' disabled selected>Select Employee</option>
                                <?php foreach ( $employees as $emp ): ?>
                                <option value="<?php echo $emp['employee_id']; ?>" <?php echo ( $b[ 'employee_id' ] == $emp[ 'employee_id' ] ) ? 'selected' : '';
?>>
                                    <?php echo htmlspecialchars( $emp[ 'name' ] );
?>
                                </option>
                                <?php endforeach;
?>
                            </select>
                            <!-- Status dropdown -->
                            <select name='status' class='form-select form-select-sm mb-2'>
                                <option value='Requested' <?=$b[ 'status' ] == 'Requested' ?'selected':'' ?>>Requested
                                </option>
                                <option value='Assigned' <?=$b[ 'status' ] == 'Assigned' ?'selected':'' ?>>Assigned
                                </option>
                                <option value='InProgress' <?=$b[ 'status' ] == 'InProgress' ?'selected':'' ?>>
                                    InProgress
                                </option>
                                <option value='Completed' <?=$b[ 'status' ] == 'Completed' ?'selected':'' ?>>Completed
                                </option>
                            </select>

                            <!-- Save button -->
                            <button class='btn btn-success btn-sm w-100'>Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach;
?>
            </tbody>
            <script>
            setTimeout(() => {
                let alertBox = document.querySelector('.alert');
                if (alertBox) alertBox.style.display = 'none';
            }, 3000);
            </script>

    </div>
</body>

</html>