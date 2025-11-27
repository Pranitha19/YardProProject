<?php

require_once __DIR__ . '/../../session_guard.php';
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$user_id = $_SESSION['user_id'];
$user = $controller->getUserDetails($user_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateProfile($user_id, $_POST);
    header("Location: editProfile.php?msg=Profile updated successfully");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile - YardPro</title>
  <link rel="stylesheet" href="../../static/css/user.css">
</head>
<body>

  <!-- Include shared navbar -->
  <?php include __DIR__ . '/../shared/navbar.php'; ?>

  <div class="edit-container">
    <h2>Edit Profile</h2>

    <?php if (!empty($_GET['msg'])): ?>
      <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php elseif (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

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

</body>
</html>
