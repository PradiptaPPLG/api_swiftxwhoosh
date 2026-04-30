<?php
include 'connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit;
}

$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID is required. Please login again."]);
    exit;
}
$schedule_id = $data['schedule_id'] ?? 0;
$coach_id = $data['coach_id'] ?? '';
$seats = $data['seats'] ?? [];
$total_price = $data['total_price'] ?? 0;

if (empty($seats)) {
    echo json_encode(["status" => "error", "message" => "No seats selected"]);
    exit;
}

pg_query($conn, "BEGIN");

try {
    $booking_code = "SWIFT-" . strtoupper(substr(md5(time() . $user_id), 0, 8));

    // 1. Insert ke bookings
    $q_booking = "INSERT INTO bookings (user_id, schedule_id, booking_code, total_price, status) 
                  VALUES ($1, $2, $3, $4, 'paid') RETURNING booking_id";
    $res_b = pg_query_params($conn, $q_booking, array($user_id, $schedule_id, $booking_code, $total_price));
    
    if (!$res_b) throw new Exception("Tabel bookings error: " . pg_last_error($conn));
    
    $booking_id = pg_fetch_assoc($res_b)['booking_id'];

    // 2. Insert ke booking_passengers
    foreach ($seats as $seat_str) {
        // Skema tabel: full_name (bukan passenger_name). seat_id adalah integer, tapi Android kirim string "1A".
        // Simpan dengan format "Passenger [coach_id]-[seat_str]" agar get_seats.php bisa membedakan antar gerbong
        $q_p = "INSERT INTO booking_passengers (booking_id, full_name) VALUES ($1, $2)";
        $res_p = pg_query_params($conn, $q_p, array($booking_id, "Passenger " . $coach_id . "-" . $seat_str));
        
        if (!$res_p) throw new Exception("Tabel booking_passengers error: " . pg_last_error($conn));
    }

    pg_query($conn, "COMMIT");
    echo json_encode(["status" => "success", "message" => "Booking Success", "code" => $booking_code]);

} catch (Exception $e) {
    pg_query($conn, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
