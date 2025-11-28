
<?php
session_start();
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$controller->logout();
?>

