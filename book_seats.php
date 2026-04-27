<?php
include 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$schedule_id = $data['schedule_id'];
$coach_id = $data['coach_id'];
$seats = $data['seats']; // Array of seat IDs

$success = true;
foreach ($seats as $seat_id) {
    $query = "INSERT INTO seat_status (schedule_id, coach_id, seat_id, is_available) 
              VALUES ($1, $2, $3, FALSE) 
              ON CONFLICT (schedule_id, coach_id, seat_id) 
              DO UPDATE SET is_available = FALSE";
    $result = pg_query_params($conn, $query, array($schedule_id, $coach_id, $seat_id));
    if (!$result) $success = false;
}

if ($success) {
    echo json_encode(array("status" => "success", "message" => "Seats booked successfully"));
} else {
    echo json_encode(array("status" => "error", "message" => "Failed to book seats"));
}
?>
