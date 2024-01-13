<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);

    $id = $_DELETE['rentedAndSold_id'];

    $stmt = $conn->prepare("DELETE FROM rented_and_sold WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'deleted successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete land');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>