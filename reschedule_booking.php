<?php
header('Content-Type: application/json');
require_once 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['booking_code']) || !isset($data['new_date'])) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

$booking_code = $data['booking_code'];
$new_date = $data['new_date'];

// Update status and potentially the date
// Note: In this schema, we might just update the status to 'rescheduled' 
// and keep the new date in a separate column or just rely on the email confirmation for now
// if the schedules table doesn't support ad-hoc date overrides per booking.
// However, to make it show up in Admin, we'll try to update the booking.

$query = "UPDATE bookings SET status = 'rescheduled' WHERE booking_code = $1";
$result = pg_query_params($dbconn, $query, array($booking_code));

if ($result && pg_affected_rows($result) > 0) {
    echo json_encode(["status" => "success", "message" => "Booking status updated to rescheduled"]);
} else {
    echo json_encode(["status" => "error", "message" => "Booking tidak ditemukan"]);
}
?>
