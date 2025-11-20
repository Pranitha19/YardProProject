<?php
session_start();
// Admin security check
if ( !isset( $_SESSION[ 'admin_logged_in' ] ) ) {
    header( 'Location: login.php' );
    exit();
}

require_once( '../../config/pdo.php' );

$sql = "SELECT 
            b.booking_id,
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
<th>Service Center</th>
<th>Service Name</th>
<th>Price</th>
<th>Status</th>
<th>Scheduled For</th>
<th>Created At</th>
</tr>
</thead>

<tbody>
<?php foreach ( $bookings as $b ): ?>
<tr>
<td><?php echo $b[ 'booking_id' ];
?></td>

<td><?php echo htmlspecialchars( $b[ 'first_name' ] . ' ' . $b[ 'last_name' ] );
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
</tr>
<?php endforeach;
?>
</tbody>
</table>

<a href = 'home.php' class = 'btn btn-secondary mt-3'>Back</a>
</div>
</body>

</html>