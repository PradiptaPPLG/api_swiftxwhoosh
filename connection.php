<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
$host = "localhost";
$port = "5432";
$dbname = "Swift";
$user = "postgres";
$password = "02032009"; // Sesuaikan dengan password PostgreSQL Anda

$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
$dbconn = pg_connect($connection_string);

if (!$dbconn) {
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "Gagal koneksi ke database"]));
}
