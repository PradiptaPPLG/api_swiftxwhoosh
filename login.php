<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
require_once 'connection.php';

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi"]);
    exit;
}

$query = "SELECT * FROM users WHERE email = $1";
$result = pg_query_params($conn, $query, array($email));
$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password_hash'])) {
    unset($user['password_hash']);
    ob_clean();
    echo json_encode([
        "status" => "success", 
        "message" => "Login berhasil",
        "user" => $user
    ]);
} else {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Email atau password salah"]);
}
