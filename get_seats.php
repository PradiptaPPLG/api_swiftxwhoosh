<?php
include 'connection.php';

$schedule_id = $_GET['schedule_id'] ?? 0;
$coach_id = $_GET['coach_id'] ?? '';

$query = "SELECT seat_id FROM seat_status WHERE schedule_id = $1 AND coach_id = $2 AND is_available = FALSE";
$result = pg_query_params($conn, $query, array($schedule_id, $coach_id));

$occupied_seats = array();
while ($row = pg_fetch_assoc($result)) {
    $occupied_seats[] = $row['seat_id'];
}

echo json_encode(array("status" => "success", "occupied_seats" => $occupied_seats));
?>
