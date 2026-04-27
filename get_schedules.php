<?php
header('Content-Type: application/json');
require_once 'connection.php';

// Ambil parameter origin dan destination (optional)
$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';

$query = "SELECT 
            s.schedule_id,
            t.train_name,
            t.train_code,
            st1.station_name AS origin_name,
            st2.station_name AS destination_name,
            TO_CHAR(s.departure_time, 'HH24:MI') AS departure_time,
            TO_CHAR(s.arrival_time, 'HH24:MI') AS arrival_time,
            s.price
          FROM schedules s
          JOIN trains t ON s.train_id = t.train_id
          JOIN stations st1 ON s.departure_station = st1.station_id
          JOIN stations st2 ON s.arrival_station = st2.station_id";

// Jika ada filter origin & destination
if (!empty($origin) && !empty($destination)) {
    $query .= " WHERE st1.station_name = $1 AND st2.station_name = $2";
    $result = pg_query_params($dbconn, $query, array($origin, $destination));
} else {
    $result = pg_query($dbconn, $query);
}

if ($result) {
    $schedules = pg_fetch_all($result);
    echo json_encode($schedules ?: []);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal mengambil jadwal"]);
}
?>
