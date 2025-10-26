<?php
//This is what admin sees after logging in.
//Starts the session ( so we can access session variables like $_SESSION[ 'role' ] ).
session_start();

// Includes the PDO file for database connection ( pdo.php ).
require_once __DIR__ . '/../../config/pdo.php';

/* Checks if the user is logged in and has role = 'admin'.If not,
sends them back to the login page. Prevents unauthorized access to dashboard. */
if ( !isset( $_SESSION[ 'role' ] ) || $_SESSION[ 'role' ] !== 'admin' ) {
    header( 'Location: adminlogin.php' );
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
<meta charset = 'utf-8'>
<title>Admin Dashboard</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'container py-4'>
<div class = 'd-flex justify-content-between align-items-center mb-4'>

<!-- Displays 'Welcome Admin'.buttons Add Employee opens the form for adding employees.
Logout will end session. -->
<h2>Welcome, < ?= $_SESSION[ 'user_name' ] ?>!</h2>
<div>
<a href = 'addemployee.php' class = 'btn btn-success'>+ Add Employee</a>
<a href = 'adminlogout.php' class = 'btn btn-danger'>Logout</a>
</div>

<h4>Dashboard Overview</h4>

<?php
/* Example: Show number of employees and bookings by running two database queries:gets total employees & gets total bookings.
fetchColumn() returns just the number. */
$empCount = $pdo->query( 'SELECT COUNT(*) FROM employees' )->fetchColumn();
$bookingCount = $pdo->query( 'SELECT COUNT(*) FROM bookings' )->fetchColumn();
?>

<div class = 'd-flex gap-3 my-4'>
<div class = 'border rounded text-center p-3 flex-fill bg-light'>
<h5>Total Employees</h5>
<p class = 'fs-3 fw-bold mb-0'>
< ?= $empCount ?>
</p>
</div>
<div class = 'border rounded text-center p-3 flex-fill bg-light'>
<h5>Total Bookings</h5>
<p class = 'fs-3 fw-bold mb-0'>
< ?= $bookingCount ?>
</p>
</div>
</div>

<hr class = 'my-4'>
<h4>Recent Bookings</h4>
<?php
/* SQL query:
FROM bookings b → main table.
LEFT JOIN employees e → combine employee names.
ON b.employee_id = e.employee_id → joins bookings with employee details.
ORDER BY b.created_at DESC → latest first.
LIMIT 10 → show only 10 latest.
fetchAll() → stores all results in $rows as an array.*/
$stmt = $pdo->query( "SELECT b.booking_id, b.service_name, b.status, b.scheduled_for, e.name AS employee_name
                    FROM bookings b
                    LEFT JOIN employees e ON b.employee_id = e.employee_id
                    ORDER BY b.created_at DESC LIMIT 10" );
$rows = $stmt->fetchAll();
?>

<table class = 'table table-striped'>
<thead>
<tr>
<th>ID</th>
<th>Service</th>
<th>Status</th>
<th>Employee</th>
<th>Scheduled</th>
</tr>
</thead>
<tbody>
<!-- Loops through $rows and prints each booking in a table row.
htmlspecialchars() prevents special characters ( like < or > ) from breaking HTML.
Now admin can see Number of employees & Number of bookings.
A list of the 10 most recent bookings.-->

<?php foreach ( $rows as $r ): ?>
<tr>
<td>
< ?= $r[ 'booking_id' ] ?>
</td>
<td>
< ?= htmlspecialchars( $r[ 'service_name' ] ) ?>
</td>
<td>
< ?= $r[ 'status' ] ?>
</td>
<td>
< ?= htmlspecialchars( $r[ 'employee_name' ] ) ?>
</td>
<td>
< ?= $r[ 'scheduled_for' ] ?>
</td>
</td>
</tr>
<?php endforeach;
?>
</tbody>
</table>
</body>

</html>