<?php
include '../../db/db_connection.php';

$sql = "SELECT * FROM houses ORDER BY house_id DESC";
$result = $conn->query($sql);

header('Content-Type: application/json');
if ($result->num_rows > 0) {
    $houses = array();
    while ($row = $result->fetch_assoc()) {
        $houses[] = $row;
    }
    echo json_encode($houses);
} else {
    echo json_encode(array());
}
?>