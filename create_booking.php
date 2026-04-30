<?php
header('Content-Type: application/json');
require_once 'connection.php';

// Ambil data JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
    exit;
}

$booking_code = $data['booking_code'] ?? '';
$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID wajib diisi. Silakan login kembali."]);
    exit;
}
$schedule_id = $data['schedule_id'] ?? 0;
$total_price = $data['total_price'] ?? 0;
$passengers = $data['passengers'] ?? [];

if (empty($booking_code) || empty($passengers)) {
    echo json_encode(["status" => "error", "message" => "Data booking atau penumpang kosong"]);
    exit;
}

// Mulai Transaksi
pg_query($dbconn, "BEGIN");

try {
    // 1. Simpan ke tabel bookings
    $query_booking = "INSERT INTO bookings (user_id, schedule_id, booking_code, total_price, status) 
                      VALUES ($1, $2, $3, $4, 'paid') RETURNING booking_id";
    $res_booking = pg_query_params($dbconn, $query_booking, array($user_id, $schedule_id, $booking_code, $total_price));
    
    if (!$res_booking) throw new Exception("Gagal simpan booking");
    
    $booking_row = pg_fetch_assoc($res_booking);
    $booking_id = $booking_row['booking_id'];

    // 2. Simpan setiap penumpang ke booking_passengers
    foreach ($passengers as $p) {
        $name = $p['name'] ?? '';
        $id_number = $p['id_number'] ?? '';
        $seat_number = $p['seat_number'] ?? ''; // Ini teks (misal S1), di skema butuh seat_id (INT)
        
        // Untuk sementara, kita simpan seat_id NULL atau 1 jika belum ada tabel seats yang sinkron
        $query_p = "INSERT INTO booking_passengers (booking_id, full_name, id_number) VALUES ($1, $2, $3)";
        $res_p = pg_query_params($dbconn, $query_p, array($booking_id, $name, $id_number));
        
        if (!$res_p) throw new Exception("Gagal simpan penumpang: $name");
    }

    pg_query($dbconn, "COMMIT");
    echo json_encode(["status" => "success", "message" => "Booking berhasil disimpan", "booking_id" => $booking_id]);

} catch (Exception $e) {
    pg_query($dbconn, "ROLLBACK");
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
