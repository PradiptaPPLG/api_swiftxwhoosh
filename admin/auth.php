<?php
session_start();
header('Content-Type: application/json');
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
        exit;
    }

    $query = "SELECT * FROM admins WHERE username = $1 LIMIT 1";
    $result = pg_query_params($conn, $query, array($username));

    if ($result && pg_num_rows($result) > 0) {
        $admin = pg_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $admin['password'])) {
            // Success
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_username'] = $admin['username'];

            // Log activity
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
            $log_query = "INSERT INTO admin_logs (admin_id, admin_name, action, target, ip_address) VALUES ($1, $2, $3, $4, $5)";
            pg_query_params($conn, $log_query, array($admin['id'], $admin['name'], 'Login', 'System', $ip_address));

            echo json_encode(['status' => 'success', 'name' => $admin['name']]);
        } else {
            // Wrong password
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }
    } else {
        // User not found
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
