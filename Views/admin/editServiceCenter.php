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

if (isset($_POST['update'])) {
    if ($controller->updateServiceCenter($_POST)) {
        setFlash('success', 'Service Center updated successfully!');
        header('Location: editServiceCenter.php');
        exit();
    } else {
        setFlash('danger', 'Failed to update Service Center.');
        header('Location: editServiceCenter.php');
        exit();
    }
}

// AJAX center details
if (isset($_GET['center_id']) && !empty($_GET['center_id'])) {
    $center_id = intval($_GET['center_id']);
    foreach ($centers as $c) {
        if ($c['center_id'] == $center_id) {
            header('Content-Type: application/json');
            echo json_encode($c);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Service Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="text-success mb-4">Edit Service Center</h3>

        <?php showFlash(); ?>

        <form method="POST" id="editForm">
            <label class="form-label">Select Service Center</label>
            <select name="center_id" id="center_id" class="form-select mb-3" required>
                <option value="">Choose...</option>
                <?php foreach($centers as $c): ?>
                    <option value="<?= $c['center_id'] ?>">
                        <?= htmlspecialchars($c['name']) ?> (ID: <?= $c['center_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <input name="name" id="name" class="form-control mb-2" placeholder="Name">
            <input name="email" id="email" class="form-control mb-2" placeholder="Email">
            <input name="phone_no" id="phone_no" class="form-control mb-2" placeholder="Phone">
            <textarea name="description" id="description" class="form-control mb-2" placeholder="Description"></textarea>
            <textarea name="address" id="address" class="form-control mb-2" placeholder="Address"></textarea>
            <input name="timings_note" id="timings_note" class="form-control mb-2" placeholder="Timings">
            <input name="base_price" id="base_price" class="form-control mb-2" placeholder="Base Price (e.g. 49.99)">
            <input name="image_url" id="image_url" class="form-control mb-3" placeholder="Image URL">

            <button name="update" class="btn btn-success">Update</button>
            <a href="home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        $("#center_id").on("change", function () {
            var id = $(this).val();
            if (id !== "") {
                $.ajax({
                    url: "editServiceCenter.php",
                    method: "GET",
                    data: { center_id: id },
                    dataType: "json",
                    success: function (data) {
                        $("#name").val(data.name);
                        $("#email").val(data.email);
                        $("#phone_no").val(data.phone_no);
                        $("#description").val(data.description);
                        $("#address").val(data.address);
                        $("#timings_note").val(data.timings_note);
                        $("#base_price").val(data.base_price);
                        $("#image_url").val(data.image_url);
                    }
                });
            } else {
                $("#editForm").find("input[type=text], input[type=number], textarea").val("");
            }
        });
    });

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
