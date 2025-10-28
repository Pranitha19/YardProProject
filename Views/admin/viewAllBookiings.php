<?php
session_start();
require_once( '../../config/pdo.php' );

$sql = "SELECT b.id AS booking_id, u.firstname, u.lastname, s.name AS center_name, b.price, b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN service_centers s ON b.servicecenter_id = s.id";
$stmt = $pdo->query( $sql );
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>All Bookings</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light'>
<div class = 'container mt-5'>
<h3 class = 'text-success mb-4'>All Bookings</h3>
<table class = 'table table-bordered table-hover'>
<thead class = 'table-success'>
<tr>
<th>ID</th>
<th>User</th>
<th>Center</th>
<th>Price</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php foreach ( $bookings as $b ): ?>
<tr>
<td>< ?= $b[ 'booking_id' ] ?></td>
<td>< ?= htmlspecialchars( $b[ 'firstname' ].' '.$b[ 'lastname' ] ) ?></td>
<td>< ?= htmlspecialchars( $b[ 'center_name' ] ) ?></td>
<td>< ?= $b[ 'price' ] ?></td>
<td>< ?= htmlspecialchars( $b[ 'status' ] ) ?></td>
</tr>
<?php endforeach;
?>
</tbody>
</table>
<a href = 'home.php' class = 'btn btn-secondary'>Back</a>
</div>
</body>

</html>