<?php
include '../../db/db_connection.php';

$sql = "SELECT * FROM apartments ORDER BY apartment_id DESC";
$result = $conn->query($sql);

header('Content-Type: application/json');
if ($result->num_rows > 0) {
    $apartments = array();
    while ($row = $result->fetch_assoc()) {
        $apartments[] = $row;
    }
    echo json_encode($apartments);
} else {
    echo json_encode(array());
}
?>