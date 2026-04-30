<?php
include 'c:/xampp/htdocs/swift-api/connection.php';
$hash = password_hash('password', PASSWORD_BCRYPT);
pg_query_params($conn, "UPDATE admins SET password = $1 WHERE username = 'admin'", array($hash));
echo "Password reset to 'password' successfully.";
?>
