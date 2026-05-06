<?php
include 'connection.php';
$res = pg_query($conn, "SELECT * FROM bookings LIMIT 1");
$row = pg_fetch_assoc($res);
echo "Columns in bookings table:\n";
print_r(array_keys($row));
?>
