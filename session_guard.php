<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user_id'])) {
  header('Location: /YardProProject/views/user/login.php?msg=Please+login');
  exit;
}
