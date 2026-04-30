<?php
session_start();
include '../connection.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $log_query = "INSERT INTO admin_logs (admin_id, admin_name, action, target, ip_address) VALUES ($1, $2, $3, $4, $5)";
    pg_query_params($conn, $log_query, array($_SESSION['admin_id'], $_SESSION['admin_name'], 'Logout', 'System', $ip_address));
}

session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
