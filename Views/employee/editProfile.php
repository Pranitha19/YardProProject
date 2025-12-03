<?php
// Controllers and helpers already loaded by index.php

if (!isset($_SESSION['employee_id'])) {
    header('Location: /YardProProject/?route=employee/login');
    exit();
}

$controller = new EmployeeController();
$employee   = $controller->getEmployee($_SESSION['employee_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['save'])) {
        $data = [
            'employee_id' => $_SESSION['employee_id'],
            'name'        => $_POST['name'],
            'phone_no'    => $_POST['phone_no'],
            'address'     => $_POST['address']
        ];

        if ($controller->updateEmployeeProfile($data)) {
            setFlash('success', 'Profile updated!');
        } else {
            setFlash('danger', 'Failed to update profile.');
        }

        header('Location: /YardProProject/?route=employee/edit-profile');
        exit();
    }

    if (isset($_POST['change_password'])) {
        if ($controller->updateEmployeePassword($_SESSION['employee_id'], $_POST['new_password'])) {
            setFlash('success', 'Password changed successfully!');
        } else {
            setFlash('danger', 'Password change failed.');
        }

        header('Location: /YardProProject/?route=employee/edit-profile');
        exit();
    }
}

// re-fetch after possible changes on previous request
$employee = $controller->getEmployee($_SESSION['employee_id']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Profile - YardPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark navbar-expand-lg" style="background:#2e7d32;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/YardProProject/?route=employee/home">YardPro Employee</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-warning" href="/YardProProject/?route=employee/logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">

    <h3 class="text-success mb-3">Edit Profile</h3>

    <?php showFlash(); ?>

    <form method="POST">
        <input name="name" class="form-control mb-3"
               value="<?= htmlspecialchars($employee['name']) ?>" required>

        <input name="phone_no" class="form-control mb-3"
               value="<?= htmlspecialchars($employee['phone_no']) ?>">

        <textarea name="address" class="form-control mb-3"><?= htmlspecialchars($employee['address']) ?></textarea>

        <button name="save" class="btn btn-success">Save Changes</button>
    </form>

    <hr>

    <h4>Change Password</h4>

    <form method="POST">
        <input name="new_password" type="password" class="form-control mb-3" placeholder="New Password" required>
        <button name="change_password" class="btn btn-warning">Update Password</button>
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
