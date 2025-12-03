<?php
require_once __DIR__ . '/../../Controllers/UserController.php';

// Validate input
if (!isset($_GET['center_id']) || !isset($_GET['date'])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

$center_id = $_GET['center_id'];
$date = $_GET['date'];

// Use MVC pattern
$controller = new UserController();
$slots = $controller->getAvailableSlots($center_id, $date);

header('Content-Type: application/json');
echo json_encode($slots);
?>
