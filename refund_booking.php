<?php
include 'connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$booking_id = $data['booking_id'];

if (!$booking_id) {
    echo json_encode(["status" => "error", "message" => "Booking ID is required"]);
    exit;
}

try {
    pg_query($conn, "BEGIN");

    // 1. Cek status & waktu (Opsional: Cek apakah > 2 jam sebelum berangkat)
    // Untuk simulasi, kita langsung izinkan refund

    // 2. Ubah status booking menjadi 'refunded'
    $query_update = "UPDATE bookings SET status = 'refunded' WHERE booking_id = $1";
    $result_update = pg_query_params($conn, $query_update, array($booking_id));

    if (!$result_update) throw new Exception("Gagal update status booking");

    // 3. Hapus detail penumpang/kursi agar kursi tersebut tersedia lagi di get_seats.php
    // Sesuai logika kita sebelumnya, kursi dibaca dari tabel booking_passengers
    $query_delete_p = "DELETE FROM booking_passengers WHERE booking_id = $1";
    $result_delete = pg_query_params($conn, $query_delete_p, array($booking_id));

    if (!$result_delete) throw new Exception("Gagal melepas kursi");

    pg_query($conn, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "Refund successful. Seats have been released."
    ]);

} catch (Exception $e) {
    pg_query($conn, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
