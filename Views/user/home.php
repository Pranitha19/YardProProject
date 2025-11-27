<?php

require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';
require_once __DIR__ . '/../../config/pdo.php';

$search = $_GET['search'] ?? '';
$controller = new UserController();
$centers = $controller->getServiceCenters($pdo, $_GET['search'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Home - YardPro</title>
  <link rel="stylesheet" href="../../static/css/user.css">
</head>
<body>

  <!-- Navbar -->
 <?php include __DIR__ . '/../shared/navbar.php'; ?>

  <!-- Search Bar -->
  <div class="search-bar">
    <form method="get" action="">
      <input type="text" name="search" placeholder="Search service centers by name..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <!-- Service Center Cards -->
  <div class="card-container">
    <?php if (count($centers) === 0): ?>
      <p class="no-results">No service centers found for "<b><?= htmlspecialchars($search) ?></b>".</p>
    <?php else: ?>
      <?php foreach ($centers as $center): ?>
        <div class="card">
          <img 
            src="<?= htmlspecialchars($center['image_url'] ?: '../../static/images/center_default.jpg') ?>" 
            alt="Service Center"
          >
          <div class="card-content">
            <h3><?= htmlspecialchars($center['name']) ?></h3>
            <p><b>Email:</b> <?= htmlspecialchars($center['email']) ?></p>
            <p><b>Phone:</b> <?= htmlspecialchars($center['phone_no']) ?></p>
            <p><b>Timings:</b> <?= htmlspecialchars($center['timings_note'] ?: '10 AM - 6 PM') ?></p>
          </div>
          <form method="post" action="bookService.php">
            <input type="hidden" name="center_id" value="<?= $center['center_id'] ?>">
            <button type="submit" class="book-btn">Book</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</body>
</html>
