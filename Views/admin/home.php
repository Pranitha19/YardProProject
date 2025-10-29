<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once("../../controllers/AdminController.php");
$controller = new AdminController();
$centers = $controller->getAllCenters();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - YardPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Our custom CSS is styles.css-->
    <link href="../../Static/css/styles.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="home.php">YardPro Admin</a>

            <!-- Search Bar -->
            <form class="d-flex ms-auto me-3" method="GET" action="">
                <input class="form-control search-input" type="search" name="q" placeholder="Search centers..."
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button class="search-btn ms-2" type="submit">Go</button>
            </form>

            <!-- Navbar Links -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="addServiceCenter.php">Add</a></li>
                <li class="nav-item"><a class="nav-link" href="editServiceCenter.php">Edit</a></li>
                <li class="nav-item"><a class="nav-link" href="deleteServiceCenter.php">Delete</a></li>
                <li class="nav-item"><a class="nav-link" href="registerEmployee.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="viewAllBookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Filter Results -->
    <?php
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = strtolower(trim($_GET['q']));
    $centers = array_filter($centers, function($c) use ($q) {
    return strpos(strtolower($c['name']), $q) !== false ||
            strpos(strtolower($c['description'] ?? ''), $q) !== false ||
            strpos(strtolower($c['address'] ?? ''), $q) !== false;
    });
echo "<div class='container mt-3'><div class='alert alert-info text-center'>Showing results for: <strong>" . htmlspecialchars($_GET['q']) . "</strong>
</div></div>";
}
?>

    <!-- Service Centers -->
    <div class="container mt-4">
        <h3 class="text-success text-center mb-4">Service Centers Overview</h3>
        <?php if (count($centers) > 0): ?>
        <div class="row g-4">
            <?php foreach ($centers as $center): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card service-card shadow-sm">
                    <img src="<?= !empty($center['image_url']) ? htmlspecialchars($center['image_url']) : '../../assets/images/default.jpg' ?>"
                        alt="Center Image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($center['name']) ?></h5>
                        <p class="card-text mb-1">
                            <?= htmlspecialchars($center['description'] ?? 'No description available.') ?></p>
                        <p class="timings mb-1"><strong>Timings:</strong>
                            <?= htmlspecialchars($center['timings_note'] ?? 'N/A') ?></p>
                        <p class="price mb-1">$<?= htmlspecialchars($center['base_price']) ?></p>
                        <p class="text-muted mb-0"><small><strong>Address:</strong>
                                <?= htmlspecialchars($center['address']) ?></small></p>
                        <p class="text-muted mb-0"><small><strong>Created:</strong>
                                <?= htmlspecialchars($center['created_at']) ?></small></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center mt-4">No service centers found.</div>
        <?php endif; ?>
    </div>

</body>

</html>