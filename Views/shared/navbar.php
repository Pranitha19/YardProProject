<?php
require_once __DIR__ . '/../../Models/VisitTracker.php';
$tracker = new VisitTracker();
$tracker->track();

// Get current route from URL parameter
$current_route = isset($_GET['route']) ? $_GET['route'] : '';
?>
<link rel="stylesheet" href="/YardProProject/Static/css/navbar.css">

<div class="navbar-custom">
  <div class="nav-left">
    <a href="/YardProProject/?route=user/edit-profile" class="<?= strpos($current_route, 'edit-profile') !== false ? 'active' : '' ?>">Edit Profile</a>
    <a href="/YardProProject/?route=user/home" class="<?= strpos($current_route, 'user/home') !== false || $current_route === '' ? 'active' : '' ?>">Home</a>
    <a href="/YardProProject/?route=user/view-bookings" class="<?= strpos($current_route, 'view-bookings') !== false || strpos($current_route, 'edit-booking') !== false ? 'active' : '' ?>">View Booking</a>
  </div>
  <div class="nav-right">
    <a href="/YardProProject/?route=user/logout">Logout</a>
  </div>
</div>
