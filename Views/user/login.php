<?php
session_start();
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Call MVC controller → model → authenticate()
    $user = $controller->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="/YardProProject/Static/css/auth.css">
</head>

<body>
     <div class="auth-card">
    <h2>YardPro</h2>
    <h3>Enter your login credentials</h3>

        <?php if (!empty($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your Email"required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your Password" required>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p> <a href="register.php" class="link">Not registered? Create an account</a></p>
    </div>

</body>

</html>
