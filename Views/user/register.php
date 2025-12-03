<?php
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Hash password before passing to model
    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $payload = [
        'first_name'    => trim($_POST['first_name']),
        'last_name'     => trim($_POST['last_name']),
        'email'         => trim($_POST['email']),
        'password_hash' => $hashed,
        'phone_no'      => trim($_POST['phone_no']),
        'address'       => trim($_POST['address'])
    ];

    $registered = $controller->register($payload);

    if ($registered) {
        setFlash('success', 'Registration successful! You can now login.');
        header('Location: /YardProProject/?route=user/login');
        exit;
    } else {
        setFlash('danger', 'Registration failed. Email may already exist.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="/YardProProject/Static/css/auth.css">
</head>

<body>

    <div class="auth-card">
    <h2>Create Your Account</h2>

        <?php showFlash(); ?>

        <form method="POST">

            <label>First Name</label>
            <input type="text" name="first_name" placeholder="Enter your First Name" required>

            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Enter your Last Name" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your Password" required>

            <label>Phone Number</label>
            <input type="text" name="phone_no" placeholder="Enter your Phone Number" required>

            <label for="address"><b>Address</b></label>
      <input type="text" name="address" placeholder="Address" required>
            <button type="submit" class="btn-primary">Register</button>
        </form>
        <a href="/YardProProject/?route=user/login" class="link">Already have an account? Login</a>
    </div>

<script>
// Flash message 
setTimeout(() => {
    const msg = document.querySelector('.flash-message');
    if (msg) {
        msg.style.transition = "opacity 0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 2000);
</script>

</body>

</html>
