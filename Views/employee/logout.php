<?php
session_start();
require_once '../../helpers/flash.php';

// Sets flash message
setFlash('success', 'Logged out successfully.');

// Removes login values only
unset($_SESSION['employee_logged_in']);
unset($_SESSION['employee_id']);
unset($_SESSION['employee_name']);

// Redirects to admin login (shared login)
header('Location: ../admin/login.php');
exit();
