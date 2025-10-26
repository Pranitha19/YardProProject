<?php
session_start();
if (empty($_SESSION['admin'])) {
  header('Location: /YardProProject/views/user/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - YardPro</title>
  <link rel="stylesheet" href="../../static/css/style.css">
</head>
<body>
  <h2 style="text-align:center; margin-top:60px;">Welcome, Admin 👋</h2>
  <p style="text-align:center;">You are now in the Admin Dashboard.</p>
  <p style="text-align:center;"><a href="../user/logout.php">Logout</a></p>
</body>
</html>
