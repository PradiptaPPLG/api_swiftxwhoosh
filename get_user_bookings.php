<?php
include 'connection.php';
header('Content-Type: application/json');

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

try {
    // Gunakan booking_date sesuai isi database Anda
    $query = "SELECT * FROM bookings WHERE user_id = $1 ORDER BY booking_date DESC";
    $result = pg_query_params($conn, $query, array((int)$user_id));

    if (!$result) {
        throw new Exception("Query Bookings Gagal: " . pg_last_error($conn));
    }

    $bookings = [];
    while ($row = pg_fetch_assoc($result)) {
        $sid = $row['schedule_id'];
        
        $q_s = "SELECT s.departure_time, t.train_code, st1.station_name as origin, st2.station_name as destination 
                FROM schedules s
                LEFT JOIN trains t ON s.train_id = t.train_id
                LEFT JOIN stations st1 ON s.departure_station = st1.station_id
                LEFT JOIN stations st2 ON s.arrival_station = st2.station_id
                WHERE s.schedule_id = $1";
        $res_s = pg_query_params($conn, $q_s, array($sid));
        $s_info = pg_fetch_assoc($res_s);

        $q_p = "SELECT string_agg(full_name, ', ') as names, COUNT(*) as count FROM booking_passengers WHERE booking_id = $1";
        $res_p = pg_query_params($conn, $q_p, array($row['booking_id']));
        $p_info = pg_fetch_assoc($res_p);

        $bookings[] = [
            "booking_id" => $row['booking_id'],
            "booking_code" => $row['booking_code'],
            "total_price" => (int)$row['total_price'],
            "status" => $row['status'],
            "order_time" => $row['booking_date'], // Pakai booking_date
            "origin_station" => $s_info['origin'] ?? "Unknown",
            "destination_station" => $s_info['destination'] ?? "Unknown",
            "departure_time" => $s_info['departure_time'] ?? $row['booking_date'],
            "train_number" => $s_info['train_code'] ?? "G-000",
            "passenger_names" => $p_info['names'] ?? "Passenger",
            "ticket_count" => (int)($p_info['count'] ?? 0)
        ];
    }

    echo json_encode(["status" => "success", "bookings" => $bookings]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
