<?php
session_start();
require_once '../../controllers/AdminController.php';
require_once '../../helpers/flash.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$controller = new AdminController();
$centers    = $controller->getAllCenters();

if (isset($_POST['delete'])) {
    if ($controller->deleteServiceCenter($_POST['center_id'])) {
        setFlash('success', 'Service Center deleted successfully!');
        header('Location: deleteServiceCenter.php');
        exit();
    } else {
        setFlash('danger', 'Error deleting Service Center!');
        header('Location: deleteServiceCenter.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Service Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="text-danger mb-4">Delete Service Center</h3>

        <?php showFlash(); ?>

        <form method="POST">
            <select name="center_id" class="form-select mb-3" required>
                <option value="">Select Center</option>
                <?php foreach ($centers as $c): ?>
                    <option value="<?= $c['center_id'] ?>">
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button name="delete" class="btn btn-danger">Delete</button>
            <a href="home.php" class="btn btn-secondary">Back</a>
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
