<?php
require_once __DIR__ . '/../../config/pdo.php';

if (!isset($_GET['center_id']) || !isset($_GET['date'])) {
  echo json_encode([]);
  exit;
}

$center_id = $_GET['center_id'];
$selected_date = $_GET['date'];
$weekday = date('N', strtotime($selected_date)); // 1 = Monday ... 7 = Sunday

$timeStmt = $pdo->prepare("
  SELECT start_time, end_time 
  FROM timings 
  WHERE center_id = :cid AND day_of_week = :dow
");
$timeStmt->execute([':cid' => $center_id, ':dow' => $weekday]);
$timing = $timeStmt->fetch();

$slots = [];

if ($timing) {
  $start = strtotime($timing['start_time']);
  $end   = strtotime($timing['end_time']);

  while ($start + 7200 <= $end) { // 2-hour slots (7200 sec)
    $slot_start = date("H:i:s", $start);
    $slot_end   = date("H:i:s", $start + 7200);

    $check = $pdo->prepare("
      SELECT COUNT(*) FROM bookings
      WHERE center_id = :cid AND booking_date = :bdate AND booking_time = :btime
    ");
    $check->execute([':cid' => $center_id, ':bdate' => $selected_date, ':btime' => $slot_start]);
    $count = $check->fetchColumn();

    if ($count >= 2) {
      $slots[] = ['slot' => "$slot_start – $slot_end (Full)", 'available' => false];
    } else {
      $slots[] = ['slot' => "$slot_start – $slot_end", 'available' => true, 'value' => $slot_start];
    }

    $start += 7200; // move by 2 hours
  }
}

header('Content-Type: application/json');
echo json_encode($slots);
