<?php
session_start();
require_once( '../../controllers/EmployeeController.php' );

if ( !isset( $_SESSION[ 'employee_logged_in' ] ) ) {
    header( 'Location: ../admin/login.php' );
    exit();
}
$controller = new EmployeeController();
$bookings = $controller->getAssignedRequests( $_SESSION[ 'employee_id' ] );
?>
<!DOCTYPE html>
<html>

<head>
<title>Employee Dashboard - YardPro</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light'>

<nav class = 'navbar navbar-dark bg-success px-3'>
<span class = 'navbar-brand'>Welcome, <?php echo $_SESSION[ 'employee_name' ];
?></span>
<a href = '../admin/logout.php' class = 'btn btn-warning'>Logout</a>
</nav>

<div class = 'container mt-4'>
<h3 class = 'text-success mb-3'>My Assigned Bookings</h3>

<table class = 'table table-bordered'>
<thead class = 'table-success'>
<tr>
<th>ID</th>
<th>Service Center</th>
<th>Service</th>
<th>Price</th>
<th>Status</th>
<th>Scheduled</th>
<th>Update</th>
</tr>
</thead>

<tbody>
<?php foreach ( $bookings as $b ): ?>
<tr>
<td><?php echo $b[ 'booking_id' ];
?></td>
<td><?php echo htmlspecialchars( $b[ 'center_name' ] );
?></td>
<td><?php echo htmlspecialchars( $b[ 'service_name' ] );
?></td>
<td>$<?php echo $b[ 'price' ];
?></td>
<td><?php echo $b[ 'status' ];
?></td>
<td><?php echo $b[ 'scheduled_for' ];
?></td>
<td>
<form method = 'POST' action = '../../controllers/EmployeeController.php'>
<input type = 'hidden' name = 'booking_id' value = "<?php echo $b['booking_id']; ?>">

<select name = 'status' class = 'form-select form-select-sm'>
<option value = 'Requested' <?php echo $b[ 'status' ] == 'Requested'?'selected':'';
?>>
Requested</option>
<option value = 'Assigned' <?php echo $b[ 'status' ] == 'Assigned'?'selected':'';
?>>Assigned
</option>
<option value = 'InProgress' <?php echo $b[ 'status' ] == 'InProgress'?'selected':'';
?>>
InProgress</option>
<option value = 'Completed' <?php echo $b[ 'status' ] == 'Completed'?'selected':'';
?>>
Completed</option>
</select>

<button class = 'btn btn-success btn-sm mt-2'>Update</button>
</form>
</td>
</tr>
<?php endforeach;
?>
</tbody>
</table>

</div>

</body>

</html>