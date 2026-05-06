<?php
include 'connection.php';
header('Content-Type: text/plain');

echo "--- MENCARI PEMILIK TIKET ---\n";

// Lihat 10 booking terakhir dan siapa user_id-nya
$res = pg_query($conn, "SELECT booking_id, booking_code, user_id, status FROM bookings ORDER BY booking_id DESC LIMIT 10");
while ($row = pg_fetch_assoc($res)) {
    echo "Booking ID: " . $row['booking_id'] . " | Code: " . $row['booking_code'] . " | Pemilik (User ID): [" . $row['user_id'] . "] | Status: " . $row['status'] . "\n";
}

// Cek apakah ada booking yang user_id-nya NULL
$res_null = pg_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE user_id IS NULL");
$row_null = pg_fetch_assoc($res_null);
echo "\nTotal Booking Tanpa Pemilik (NULL): " . $row_null['total'] . "\n";

?>
