<?php
header('Content-Type: application/json');
require_once 'connection.php';

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "User ID diperlukan"]);
    exit;
}

$query = "SELECT * FROM bookings WHERE user_id = $1 ORDER BY created_at DESC";
$result = pg_query_params($dbconn, $query, array($user_id));
$bookings = pg_fetch_all($result);

if ($bookings) {
    echo json_encode(["status" => "success", "data" => $bookings]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}
?>
