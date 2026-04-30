<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";
$result = pg_query($conn, $query);

echo "Tables:\n";
while ($row = pg_fetch_assoc($result)) {
    echo $row['table_name'] . "\n";
}
?>
