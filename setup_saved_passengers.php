<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'connection.php';

// Buat tabel saved_passengers jika belum ada
$create_table = "CREATE TABLE IF NOT EXISTS saved_passengers (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(user_id),
    name VARCHAR(100) NOT NULL,
    identity_type VARCHAR(20),
    identity_number VARCHAR(30),
    gender VARCHAR(10),
    date_of_birth VARCHAR(20),
    phone VARCHAR(20),
    email VARCHAR(100),
    UNIQUE(user_id, identity_number)
)";

$result = pg_query($conn, $create_table);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Tabel saved_passengers berhasil dibuat"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($conn)]);
}
?>
