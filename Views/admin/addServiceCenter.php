<?php
session_start();
require_once( '../../controllers/AdminController.php' );
$controller = new AdminController();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    if ( $controller->addServiceCenter( $_POST ) ) {
        $msg = 'Service Center added successfully!';
    } else {
        $error = 'Error adding service center!';
    }
}
?>
<!DOCTYPE html>
<html lang = 'en'>

<head>
<meta charset = 'UTF-8'>
<title>Add Service Center</title>
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel = 'stylesheet'>
</head>

<body class = 'bg-light'>
<div class = 'container mt-5'>
<h3 class = 'text-success mb-4'>Add Service Center</h3>
<?php if ( !empty( $msg ) ) {
    echo "<div class='alert alert-success'>$msg</div>";
}
?>
<?php if ( !empty( $error ) ) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method = 'POST'>
<input name = 'name' class = 'form-control mb-2' placeholder = 'Center Name *' required>
<input name = 'email' class = 'form-control mb-2' placeholder = 'Email'>
<input name = 'phone_no' class = 'form-control mb-2' placeholder = 'Phone'>
<textarea name = 'description' class = 'form-control mb-2' placeholder = 'Description'></textarea>
<textarea name = 'address' class = 'form-control mb-2' placeholder = 'Address *' required></textarea>
<input name = 'timings_note' class = 'form-control mb-2' placeholder = 'Timings (e.g., Mon–Fri 9AM–6PM)'>
<input type = 'number' step = '0.01' name = 'base_price' class = 'form-control mb-2'
placeholder = 'Base Price (e.g., 49.99)'>
<input name = 'image_url' class = 'form-control mb-3'
placeholder = '/Applications/XAMPP/xamppfiles/htdocs/YardProProject/Static/images/yardpro.png'>
<button class = 'btn btn-success'>Add Center</button>
<a href = 'home.php' class = 'btn btn-secondary'>Back</a>
</form>

<p class = 'text-muted mt-3 mb-0' style = 'font-size: .9rem;'>
<strong>Note:</strong> <code>created_at</code> is set automatically by the database.
</p>
</div>
</body>

</html>