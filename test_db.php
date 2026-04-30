<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

if (!$conn) {
    echo "Koneksi GAGAL: " . pg_last_error();
} else {
    echo "Koneksi BERHASIL! Status: " . pg_connection_status($conn);
    
    // Cek tabel
    $result = pg_query($conn, "SELECT count(*) FROM users");
    if ($result) {
        $row = pg_fetch_row($result);
        echo "\nJumlah User: " . $row[0];
    } else {
        echo "\nQuery Gagal: " . pg_last_error($conn);
    }
}
?>
