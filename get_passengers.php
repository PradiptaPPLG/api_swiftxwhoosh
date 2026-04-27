<?php
include 'connection.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID diperlukan"]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM saved_passengers WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(["status" => "success", "data" => $passengers]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
