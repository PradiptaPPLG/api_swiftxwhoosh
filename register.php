<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
require_once 'connection.php';

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Semua field harus diisi"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (full_name, email, phone, password_hash) VALUES ($1, $2, $3, $4)";
$result = pg_query_params($conn, $query, array($full_name, $email, $phone, $hashed_password));

if ($result) {
    ob_clean();
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Registrasi gagal, email atau nomor hp mungkin sudah terdaftar"]);
}
