<?php
//Shows the employee all their assigned bookings.
session_start();
require_once __DIR__ . '/../../config/pdo.php';
if ( !isset( $_SESSION[ 'role' ] ) || $_SESSION[ 'role' ] !== 'employee' ) {
    header( 'Location: login.php' );
    exit;
}

$emp_id = $_SESSION[ 'user_id' ];
/* Finds all bookings that belong to the logged-in employee.
Also joins service center names. */

$stmt = $pdo->prepare( "SELECT b.booking_id, b.service_name, b.status, b.scheduled_for, sc.name AS center_name
                FROM bookings b
                LEFT JOIN service_centers sc ON b.center_id = sc.center_id
                WHERE b.employee_id = ? ORDER BY b.created_at DESC" );
$stmt->execute( [ $emp_id ] );
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
<meta charset = 'utf-8'>
<title>View Assigned Requests</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'container py-4'>
<div class = 'd-flex justify-content-between mb-3'>
<h3>Assigned Requests</h3>
<div>
<a href = 'editprofile.php' class = 'btn btn-outline-primary btn-sm'>Edit Profile</a>
<a href = 'logout.php' class = 'btn btn-outline-danger btn-sm'>Logout</a>
</div>
</div>

<table class = 'table table-bordered'>
<thead>
<tr>
<th>ID</th>
<th>Service</th>
<th>Center</th>
<th>Scheduled</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ( $requests as $r ): ?>
<tr>
<td>
< ?= $r[ 'booking_id' ]?>
</td>
<td>
< ?= htmlspecialchars( $r[ 'service_name' ] )?>
</td>
<td>
< ?= htmlspecialchars( $r[ 'center_name' ] )?>
</td>
<td>
< ?= $r[ 'scheduled_for' ]?>
</td>
<td>
< ?= $r[ 'status' ]?>
</td>
<td>
<form method = 'post' action = 'updatestatus.php' class = 'd-flex gap-1'>
<input type = 'hidden' name = 'booking_id' value = "<?=$r['booking_id']?>">
<select name = 'status' class = 'form-select form-select-sm'>
<option value = 'InProgress'>In Progress</option>
<option value = 'Completed'>Completed</option>
<option value = 'Cancelled'>Cancelled</option>
</select>
<button type = 'submit' class = 'btn btn-sm btn-success'>Update</button>
</form>
</td>
</tr>
<?php endforeach;
?>
</tbody>
</table>
</body>

</html>