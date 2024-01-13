<?php
include '../../db/db_connection.php';

$sql = "SELECT * FROM lands ORDER BY land_id DESC";
$result = $conn->query($sql);

header('Content-Type: application/json');
if ($result->num_rows > 0) {
    $lands = array();
    while ($row = $result->fetch_assoc()) {
        $lands[] = $row;
    }
    echo json_encode($lands);
} else {
    echo json_encode(array());
}
?>