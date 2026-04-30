<?php
include 'connection.php';

// Script untuk mengupdate database dengan fitur Seat Locking
$queries = [
    "CREATE TABLE IF NOT EXISTS seat_locks (
        lock_id SERIAL PRIMARY KEY,
        schedule_id INTEGER NOT NULL,
        seat_id VARCHAR(10) NOT NULL,
        user_id INTEGER,
        locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NOT NULL
    )",
    "CREATE INDEX IF NOT EXISTS idx_seat_locks_expiry ON seat_locks (expires_at)",
    // Tambahkan kolom status di tabel bookings jika belum ada logic bayar otomatis
    "ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'paid'" 
];

foreach ($queries as $q) {
    pg_query($conn, $q);
}

echo "Database updated with Seat Locking feature.";
?>
