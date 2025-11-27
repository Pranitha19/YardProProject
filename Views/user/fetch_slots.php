<?php
require_once __DIR__ . '/../../config/pdo.php';

if (!isset($_GET['center_id']) || !isset($_GET['date'])) {
  echo json_encode([]);
  exit;
}

$center_id = $_GET['center_id'];
$selected_date = $_GET['date'];

$weekday = date('N', strtotime($selected_date)); // 1=Mon ... 7=Sun

// Fetch timings for selected day
$timeStmt = $pdo->prepare("
  SELECT start_time, end_time 
  FROM timings 
  WHERE center_id = :cid AND day_of_week = :dow
");
$timeStmt->execute([':cid' => $center_id, ':dow' => $weekday]);
$timing = $timeStmt->fetch();

$slots = [];

if ($timing) {
  
  $start = strtotime($timing['start_time']);   // e.g., 10:00:00
  $end   = strtotime($timing['end_time']);     // e.g., 18:00:00

  // Generate 2-hour slots
  while ($start + 7200 <= $end) {

    $slot_start = date("H:i:s", $start);
    $slot_end   = date("H:i:s", $start + 7200);

    // Format for display (friendly 12-hr format)
    $slot_label = date("h:i A", $start) . " – " . date("h:i A", $start + 7200);

    // Check if already booked
    $check = $pdo->prepare("
      SELECT COUNT(*) FROM bookings
      WHERE center_id = :cid AND booking_date = :bdate AND booking_time = :btime
    ");
    $check->execute([':cid' => $center_id, ':bdate' => $selected_date, ':btime' => $slot_start]);
    $count = $check->fetchColumn();

    // Capacity = 2 bookings per slot
    $available = ($count < 2);

    $slots[] = [
      'slot'      => $slot_label,
      'value'     => $slot_start,
      'available' => $available
    ];

    $start += 7200; // +2 hours
  }
}

header('Content-Type: application/json');
echo json_encode($slots);
