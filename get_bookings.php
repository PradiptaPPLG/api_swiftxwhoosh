<?php
header('Content-Type: application/json');
require_once 'connection.php';

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "User ID diperlukan"]);
    exit;
}

// Join bookings with schedules, trains, and stations for complete ticket info
$query = "SELECT b.booking_id, b.booking_code, b.booking_date, b.total_price, b.status,
                 s.schedule_id, s.departure_time, s.arrival_time, s.price,
                 t.train_name, t.train_code,
                 st_dep.station_name AS origin_name,
                 st_arr.station_name AS destination_name
          FROM bookings b
          LEFT JOIN schedules s ON b.schedule_id = s.schedule_id
          LEFT JOIN trains t ON s.train_id = t.train_id
          LEFT JOIN stations st_dep ON s.departure_station = st_dep.station_id
          LEFT JOIN stations st_arr ON s.arrival_station = st_arr.station_id
          WHERE b.user_id = $1
          ORDER BY b.booking_date DESC";

$result = pg_query_params($conn, $query, array($user_id));
$bookings = pg_fetch_all($result);

if ($bookings) {
    // Also fetch passengers for each booking
    foreach ($bookings as &$booking) {
        $p_query = "SELECT * FROM booking_passengers WHERE booking_id = $1";
        $p_result = pg_query_params($conn, $p_query, array($booking['booking_id']));
        $booking['passengers'] = pg_fetch_all($p_result) ?: [];
    }
    echo json_encode(["status" => "success", "data" => $bookings]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}
?>
