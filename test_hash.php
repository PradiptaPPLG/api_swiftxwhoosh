<?php
include 'c:/xampp/htdocs/swift-api/connection.php';
$result = pg_query($conn, "SELECT password FROM admins WHERE username='admin'");
$row = pg_fetch_assoc($result);
echo "Hash in DB: " . $row['password'] . "\n";
echo "Verify password 'password': " . (password_verify('password', $row['password']) ? 'true' : 'false') . "\n";
?>
