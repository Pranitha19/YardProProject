<?php
setFlash('success', 'Logged out successfully.');
// removes login values only
unset($_SESSION['employee_id']);
unset($_SESSION['employee_name']);

header('Location: /YardProProject/?route=admin/login');
exit();
