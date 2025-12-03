<?php

require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$user_id = $_SESSION['user_id'];
$user = $controller->getUserDetails($user_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateProfile($user_id, $_POST);
    setFlash('success', 'Profile updated successfully!');
    header("Location: /YardProProject/?route=user/edit-profile");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile - YardPro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/YardProProject/Static/css/edit_profile.css">
</head>
<body>

   <?php include __DIR__ . '/../shared/navbar.php'; ?>


  <div class="edit-container">
    <h2>Edit Profile</h2>

    <?php showFlash(); ?>

    <form method="post">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

      <label>Phone Number</label>
      <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>">

      <label>Address</label>
      <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">

      <button type="submit" class="btn-primary">Update Profile</button>
    </form>
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
