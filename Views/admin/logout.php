<?php
// Stores flash
setFlash('success', 'Logged out successfully.');
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_name']);
// Redirect
header('Location: /YardProProject/?route=admin/login');
exit();
