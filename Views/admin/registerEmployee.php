<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: /YardProProject/?route=admin/login');
    exit();
}

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
        $error = 'Name, Email, and Password are required.';
    } else {
        if ($controller->registerEmployee($_POST)) {
            setFlash('success', 'Employee registered successfully!');
        } else {
            setFlash('danger', 'Error registering employee. Email may already exist.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../Static/css/styles.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <h3 class="text-success mb-4">Register New Employee</h3>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php showFlash(); ?>
        <form method="POST">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control mb-3" required>

            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control mb-3" required>

            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control mb-3" required>

            <label class="form-label">Phone No</label>
            <input type="text" name="phone_no" class="form-control mb-3">

            <label class="form-label">Address</label>
            <textarea name="address" class="form-control mb-3"></textarea>

            <button class="btn btn-success w-100">Register Employee</button>
            <a href="/YardProProject/?route=admin/home" class="btn btn-secondary w-100 mt-3">Back</a>
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
    }, 2000);
    </script>
</body>

</html>
