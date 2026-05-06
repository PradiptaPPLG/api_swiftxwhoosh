<?php
include 'connection.php';
$res = pg_query($conn, "SELECT schedule_id FROM schedules");
$ids = [];
while($row = pg_fetch_assoc($res)) {
    $ids[] = $row['schedule_id'];
}
echo "Available Schedule IDs: " . implode(', ', $ids);
?>
