<?php
include 'connection.php';
header('Content-Type: text/plain');

echo "--- CHECKING TABLES ---\n";

// 1. Cek isi tabel bookings
echo "\n[BOOKINGS TABLE]\n";
$res = pg_query($conn, "SELECT * FROM bookings ORDER BY booking_id DESC LIMIT 5");
while ($row = pg_fetch_assoc($res)) {
    print_r($row);
}

// 2. Cek isi tabel schedules
echo "\n[SCHEDULES TABLE]\n";
$res = pg_query($conn, "SELECT schedule_id, departure_station, arrival_station FROM schedules LIMIT 5");
while ($row = pg_fetch_assoc($res)) {
    print_r($row);
}

// 3. Cek isi tabel users
echo "\n[USERS TABLE]\n";
$res = pg_query($conn, "SELECT user_id, email, full_name FROM users ORDER BY user_id DESC LIMIT 5");
while ($row = pg_fetch_assoc($res)) {
    print_r($row);
}

// 4. Test Join yang kita pakai di MyTickets
echo "\n[TEST JOIN QUERY]\n";
$query = "SELECT b.booking_id, b.booking_code, s.schedule_id 
          FROM bookings b 
          JOIN schedules s ON b.schedule_id = s.schedule_id";
$res = pg_query($conn, $query);
if ($res) {
    echo "Join Success! Rows found: " . pg_num_rows($res) . "\n";
    while ($row = pg_fetch_assoc($res)) {
        print_r($row);
    }
} else {
    echo "Join Failed: " . pg_last_error($conn) . "\n";
}
?>
