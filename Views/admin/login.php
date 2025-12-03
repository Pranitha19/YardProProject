<?php
$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $adminRow = $controller->login($email, $password);

    if ($adminRow) {
        $_SESSION['admin_id'] = $adminRow['admin_id'];
        $_SESSION['admin_email']     = $adminRow['email'] ?? $email;
        $_SESSION['admin_name']      = $adminRow['name'] ?? 'Admin';
        setFlash('success', 'Logged in successfully as Admin.');
        header('Location: /YardProProject/?route=admin/home');
        exit();
    }

    $stmt = $pdo->prepare('SELECT * FROM employees WHERE email = ?');
    $stmt->execute([$email]);
    $employee = $stmt->fetch();

    if ($employee && password_verify($password, $employee['password_hash'])) {
        $_SESSION['employee_id']        = $employee['employee_id'];
        $_SESSION['employee_name']      = $employee['name'];
        setFlash('success', 'Logged in successfully as Employee.');
        header('Location: /YardProProject/?route=employee/home');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin & Employee Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 22rem;">
        <h4 class="text-center text-success mb-3">Admin & Employee Login</h4>

        <?php showFlash(); ?>

        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button class="btn btn-success w-100">Login</button>
        </form>
    </div>

    <script>
    setTimeout(() => {
        const msg = document.querySelector('.flash-message');
        if (msg) {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        }
    }, 3000);
    </script>
</body>

</html>
