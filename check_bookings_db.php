<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$query = "SELECT * FROM bookings ORDER BY booking_id DESC LIMIT 10";
$result = pg_query($conn, $query);

echo "All Bookings:\n";
while ($row = pg_fetch_assoc($result)) {
    echo json_encode($row) . "\n";
}

$query2 = "SELECT * FROM booking_passengers ORDER BY passenger_id DESC LIMIT 10";
$result2 = pg_query($conn, $query2);

echo "\nAll Passengers:\n";
while ($row = pg_fetch_assoc($result2)) {
    echo json_encode($row) . "\n";
}
?>
