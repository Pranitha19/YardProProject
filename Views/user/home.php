<?php
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../Controllers/UserController.php';
$controller = new UserController();

// get search value
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$centers = $controller->getServiceCenters($search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home - YardPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/YardProProject/Static/css/user.css">
</head>
<body>

<?php include __DIR__ . '/../shared/navbar.php'; ?>
 <?php showFlash(); ?>
<div class="search-bar">
    <form method="get" action="/YardProProject/index.php">
        <input type="hidden" name="route" value="user/home">
        <input type="text" name="search"
               placeholder="Search service centers by name..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="card-container">
    <?php if (empty($centers)): ?>
        <p class="no-results">
            No service centers found for "<b><?= htmlspecialchars($search) ?></b>".
        </p>

    <?php else: ?>
        <?php foreach ($centers as $center): ?>
            <div class="card">
                <img 
                    src="<?= htmlspecialchars($center['image_url'] ?: '/YardProProject/Static/images/center_default.jpg') ?>"
                    alt="Service Center"
                >

                <div class="card-content">
                    <h3><?= htmlspecialchars($center['name']) ?></h3>
                    <p><b>Email:</b> <?= htmlspecialchars($center['email']) ?></p>
                    <p><b>Phone:</b> <?= htmlspecialchars($center['phone_no']) ?></p>
                    <p><b>Timings:</b> <?= htmlspecialchars($center['timings_note'] ?: '10 AM - 6 PM') ?></p>
                </div>

                <form method="post" action="/YardProProject/?route=user/book-service">
                    <input type="hidden" name="center_id" value="<?= $center['center_id'] ?>">
                    <button type="submit" class="book-btn">Book</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
