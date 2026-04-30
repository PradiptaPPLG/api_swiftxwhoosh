<?php
include 'connection.php';
header('Content-Type: application/json');

// LOG DEBUG (Akan muncul di log Apache jika ada error)
error_log("Save Passenger Call Received");

$data = json_decode(file_get_contents("php://input"), true);

// Jika bukan JSON, coba $_POST
$user_id = $data['user_id'] ?? ($_POST['user_id'] ?? null);
$name = $data['name'] ?? ($_POST['name'] ?? null);
$identity_type = $data['identity_type'] ?? ($_POST['identity_type'] ?? null);
$identity_number = $data['identity_number'] ?? ($_POST['identity_number'] ?? null);
$gender = $data['gender'] ?? ($_POST['gender'] ?? null);
$date_of_birth = $data['date_of_birth'] ?? ($_POST['date_of_birth'] ?? null);
$phone = $data['phone'] ?? ($_POST['phone'] ?? null);
$email = $data['email'] ?? ($_POST['email'] ?? null);

if (!$user_id || !$name) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap. user_id: $user_id, name: $name"]);
    exit;
}

// Gunakan query yang paling basic dulu untuk ngetes
$sql = "INSERT INTO saved_passengers (user_id, name, identity_type, identity_number, gender, date_of_birth, phone, email) 
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
        ON CONFLICT (user_id, identity_number) 
        DO UPDATE SET name = EXCLUDED.name, phone = EXCLUDED.phone, email = EXCLUDED.email";

$result = pg_query_params($conn, $sql, array($user_id, $name, $identity_type, $identity_number, $gender, $date_of_birth, $phone, $email));

if ($result) {
    echo json_encode(["status" => "success", "message" => "Passenger saved"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($conn)]);
}
?>
