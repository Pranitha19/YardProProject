<?php
session_start();
require_once '../../helpers/flash.php';

// Stores flash
setFlash('success', 'Logged out successfully.');
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_name']);

// we don't call session_unset() (removes flash), we dont even do session_destroy()

// Redirect
header('Location: login.php');
exit();
