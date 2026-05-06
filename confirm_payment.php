<?php
include 'connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['booking_id'])) {
    echo json_encode(["status" => "error", "message" => "Booking ID required"]);
    exit;
}

$booking_id = $data['booking_id'];

// Update status dari pending ke paid
$query = "UPDATE bookings SET status = 'paid' WHERE booking_id = $1 AND status = 'pending'";
$result = pg_query_params($conn, $query, array($booking_id));

if ($result && pg_affected_rows($result) > 0) {
    echo json_encode(["status" => "success", "message" => "Payment verified, status updated to PAID"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update status or booking not found/already paid"]);
}
?>
