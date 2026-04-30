<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'connection.php';

$schedule_id = $_GET['schedule_id'] ?? 0;

// 1. Ambil kursi yang sudah di-booking (status paid)
// Karena seat_id di database berupa integer tapi kita kirim string dari Android ("1A"), 
// seat string-nya sementara disimpan di dalam full_name dengan format "Passenger 1A"
$query_booked = "SELECT SUBSTRING(bp.full_name FROM 11) AS seat_str 
                 FROM booking_passengers bp 
                 JOIN bookings b ON bp.booking_id = b.booking_id 
                 WHERE b.schedule_id = $1 AND b.status = 'paid' AND bp.full_name LIKE 'Passenger %'";

$booked_result = pg_query_params($conn, $query_booked, array($schedule_id));

$occupied_seats = array();

if ($booked_result) {
    while ($row = pg_fetch_assoc($booked_result)) {
        $occupied_seats[] = (string)$row['seat_str'];
    }
}

echo json_encode(array("status" => "success", "occupied_seats" => array_values(array_unique($occupied_seats))));
?>
