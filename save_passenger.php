<?php
include 'connection.php';

$user_id = $_POST['user_id'] ?? null;
$name = $_POST['name'] ?? null;
$identity_type = $_POST['identity_type'] ?? null;
$identity_number = $_POST['identity_number'] ?? null;
$gender = $_POST['gender'] ?? null;
$date_of_birth = $_POST['date_of_birth'] ?? null;
$phone = $_POST['phone'] ?? null;
$email = $_POST['email'] ?? null;

if (!$user_id || !$name || !$identity_number) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

try {
    // Check if already exists
    $stmt = $conn->prepare("SELECT id FROM saved_passengers WHERE user_id = ? AND identity_number = ?");
    $stmt->execute([$user_id, $identity_number]);
    
    if ($stmt->fetch()) {
        // Update existing
        $sql = "UPDATE saved_passengers SET name = ?, identity_type = ?, gender = ?, date_of_birth = ?, phone = ?, email = ? WHERE user_id = ? AND identity_number = ?";
        $conn->prepare($sql)->execute([$name, $identity_type, $gender, $date_of_birth, $phone, $email, $user_id, $identity_number]);
    } else {
        // Insert new
        $sql = "INSERT INTO saved_passengers (user_id, name, identity_type, identity_number, gender, date_of_birth, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $conn->prepare($sql)->execute([$user_id, $name, $identity_type, $identity_number, $gender, $date_of_birth, $phone, $email]);
    }
    
    echo json_encode(["status" => "success", "message" => "Data penumpang disimpan"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
