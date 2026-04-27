<?php
include 'connection.php';

// Tabel Penumpang
$table_passengers = "saved_passengers";
$query_p = "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = '$table_passengers');";
$res_p = pg_query($conn, $query_p);
$row_p = pg_fetch_row($res_p);

if ($row_p[0] != 't') {
    echo "Membuat tabel '$table_passengers'...";
    $create_p_sql = "
    CREATE TABLE $table_passengers (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        identity_type VARCHAR(50) NOT NULL,
        identity_number VARCHAR(100) NOT NULL,
        gender VARCHAR(20),
        date_of_birth DATE,
        phone VARCHAR(20),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, identity_number)
    );";
    pg_query($conn, $create_p_sql);
} else {
    echo "Tabel '$table_passengers' sudah ada. ";
}

// Tabel Status Kursi
$table_seats = "seat_status";
$query_s = "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = '$table_seats');";
$res_s = pg_query($conn, $query_s);
$row_s = pg_fetch_row($res_s);

if ($row_s[0] != 't') {
    echo "Membuat tabel '$table_seats'...";
    $create_s_sql = "
    CREATE TABLE $table_seats (
        id SERIAL PRIMARY KEY,
        schedule_id INT NOT NULL,
        coach_id VARCHAR(10) NOT NULL,
        seat_id VARCHAR(10) NOT NULL,
        is_available BOOLEAN DEFAULT FALSE,
        UNIQUE(schedule_id, coach_id, seat_id)
    );";
    pg_query($conn, $create_s_sql);
} else {
    echo "Tabel '$table_seats' sudah ada.";
}
?>
