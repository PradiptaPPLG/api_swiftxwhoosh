<?php
include 'connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$user_id = $data['user_id'];
$schedule_id = $data['schedule_id'];
$total_price = $data['total_price'];
$passenger_names = $data['passenger_names']; 
$seats = $data['seats'];
$booking_code = "SWIFT-" . strtoupper(substr(md5(time() . $user_id), 0, 8));

try {
    pg_query($conn, "BEGIN");

    // 1. Insert ke bookings
    $query_booking = "INSERT INTO bookings (user_id, schedule_id, booking_code, total_price, status, booking_date) 
                      VALUES ($1, $2, $3, $4, 'pending', NOW()) RETURNING booking_id";
    $result_booking = pg_query_params($conn, $query_booking, array($user_id, $schedule_id, $booking_code, $total_price));
    
    if (!$result_booking) throw new Exception("Gagal membuat booking: " . pg_last_error($conn));
    
    $booking_id = pg_fetch_result($result_booking, 0, 0);

    // 2. Insert detail penumpang + NOMOR KURSI (Format: "Passenger CoachID-SeatID")
    foreach ($passenger_names as $index => $name) {
        $seat = $seats[$index] ?? "N/A";
        $coach_id = $data['coach_id'] ?? "01"; 
        $entry_name = "Passenger " . $coach_id . "-" . $seat; 
        $query_p = "INSERT INTO booking_passengers (booking_id, full_name) VALUES ($1, $2)";
        $res_p = pg_query_params($conn, $query_p, array($booking_id, $entry_name));
        if (!$res_p) throw new Exception("Gagal simpan data penumpang");
    }

    pg_query($conn, "COMMIT");

    echo json_encode([
        "status" => "success", 
        "message" => "Booking created (Pending)", 
        "booking_id" => $booking_id,
        "booking_code" => $booking_code
    ]);

} catch (Exception $e) {
    pg_query($conn, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
