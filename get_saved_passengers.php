<?php
header('Content-Type: application/json');
include 'connection.php';

$user_id = $_GET['user_id'] ?? 0;

if ($user_id == 0) {
    echo json_encode(array("status" => "error", "message" => "Invalid user_id"));
    exit;
}

$query = "SELECT * FROM saved_passengers WHERE user_id = $1 ORDER BY id DESC";
$result = pg_query_params($conn, $query, array($user_id));

$passengers = array();
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $passengers[] = array(
            "name" => $row['name'],
            "identity_type" => $row['identity_type'],
            "identity_number" => $row['identity_number'],
            "gender" => $row['gender'],
            "date_of_birth" => $row['date_of_birth'] ?? "",
            "phone" => $row['phone'] ?? "",
            "email" => $row['email'] ?? ""
        );
    }
}

echo json_encode(array("status" => "success", "passengers" => $passengers));
?>
