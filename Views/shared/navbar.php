<?php
require_once __DIR__ . '/../Models/VisitTracker.php';
$tracker = new VisitTracker();
$tracker->track();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="/YardProProject/static/css/navbar.css">

<div class="navbar">
  <div class="nav-left">
    <a href="../user/editProfile.php" class="<?= $current_page === 'editProfile.php' ? 'active' : '' ?>">Edit Profile</a>
     <a href="../user/home.php" class="<?= $current_page === 'home.php' ? 'active' : '' ?>">Home</a>
    <a href="../user/viewBookings.php" class="<?= $current_page === ('viewBookings.php'||'editBooking.php') ? 'active' : '' ?>">View Booking</a>
     </div>
  <div class="nav-right">
   <a href="../user/logout.php">Logout</a>
  </div>
</div>
