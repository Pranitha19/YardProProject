<?php
session_start();
// Helpers already loaded by index.php

// Sets flash message
setFlash('success', 'Logged out successfully.');

// Removes login values only
unset($_SESSION['employee_id']);
unset($_SESSION['employee_name']);

// Redirects to employee login (or shared admin login)
header('Location: /YardProProject/?route=employee/login');
exit();
