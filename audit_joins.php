<?php
include 'connection.php';
$res = pg_query($conn, "SELECT * FROM schedules WHERE schedule_id = 17");
print_r(pg_fetch_assoc($res));

$res2 = pg_query($conn, "SELECT * FROM trains LIMIT 5");
echo "\nTrains:\n";
while($row = pg_fetch_assoc($res2)) { print_r($row); }

$res3 = pg_query($conn, "SELECT * FROM stations LIMIT 5");
echo "\nStations:\n";
while($row = pg_fetch_assoc($res3)) { print_r($row); }
?>
