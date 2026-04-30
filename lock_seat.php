<?php
header('Content-Type: application/json');
include 'connection.php';

// Tabel seat_locks tidak ada di database saat ini.
// API ini hanya mengembalikan success agar Android tidak crash.
// Fungsi lock/unlock kursi bisa diimplementasikan nanti jika tabel seat_locks dibuat.

echo json_encode(array("status" => "success"));
?>
