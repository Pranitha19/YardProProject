<?php
session_start();
require_once( '../../controllers/AdminController.php' );
$controller = new AdminController();
$centers = $controller->getAllCenters();

if ( isset( $_POST[ 'delete' ] ) ) {
    if ( $controller->deleteServiceCenter( $_POST[ 'center_id' ] ) ) {
        $msg = 'Service Center deleted successfully!';
    } else {
        $error = 'Error deleting Service Center!';
    }
}
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>Delete Service Center</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light'>
<div class = 'container mt-5'>
<h3 class = 'text-danger mb-4'>Delete Service Center</h3>
<?php if ( !empty( $msg ) ) echo "<div class='alert alert-success'>$msg</div>";
?>
<?php if ( !empty( $error ) ) echo "<div class='alert alert-danger'>$error</div>";
?>
<form method = 'POST'>
<select name = 'center_id' class = 'form-select mb-3' required>
<option value = ''>Select Center</option>
<?php foreach ( $centers as $c ): ?>
<option value = "<?php echo $c['center_id']; ?>">
<?php echo htmlspecialchars( $c[ 'name' ] );
?>
</option>
<?php endforeach;
?>
</select>
<button name = 'delete' class = 'btn btn-danger'>Delete</button>
<a href = 'home.php' class = 'btn btn-secondary'>Back</a>
</form>
</div>
</body>

</html>