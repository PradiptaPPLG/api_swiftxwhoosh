<?php
include 'connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$booking_id = $data['booking_id'];
$new_schedule_id = $data['new_schedule_id'];
$new_seats = $data['seats']; // Array of new seats: ["1A", "1B"]
$passenger_names = $data['passenger_names']; // To maintain continuity

if (!$booking_id || !$new_schedule_id) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

try {
    pg_query($conn, "BEGIN");

    // 1. Update Booking dengan Jadwal Baru
    $query_update = "UPDATE bookings SET schedule_id = $1, status = 'paid' WHERE booking_id = $2";
    $result_update = pg_query_params($conn, $query_update, array($new_schedule_id, $booking_id));

    if (!$result_update) throw new Exception("Gagal update jadwal booking");

    // 2. Hapus kursi lama
    $query_delete = "DELETE FROM booking_passengers WHERE booking_id = $1";
    pg_query_params($conn, $query_delete, array($booking_id));

    // 3. Masukkan kursi baru (Format: Passenger Gerbong-Seat)
    foreach ($new_seats as $index => $seat) {
        $coach_id = $data['coach_id'] ?? "01";
        $entry_name = "Passenger " . $coach_id . "-" . $seat; 
        $query_p = "INSERT INTO booking_passengers (booking_id, full_name) VALUES ($1, $2)";
        pg_query_params($conn, $query_p, array($booking_id, $entry_name));
    }

    pg_query($conn, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "Reschedule successful. New schedule and seats assigned."
    ]);

} catch (Exception $e) {
    pg_query($conn, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
