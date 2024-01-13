<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);

    $houseId = $_DELETE['house_id'];

    $stmt = $conn->prepare("DELETE FROM houses WHERE house_id = ?");
    $stmt->bind_param("i", $houseId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'House deleted successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete house');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>