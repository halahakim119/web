<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['land_id'])) {
        $landId = $_GET['land_id'];

        $stmt = $conn->prepare("SELECT * FROM lands WHERE land_id = ?");
        $stmt->bind_param("i", $landId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $land = $result->fetch_assoc();
            $response = array('status' => 'success', 'land' => $land);
        } else {
            $response = array('status' => 'error', 'message' => 'Land not found');
        }

        echo json_encode($response);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Missing land_id parameter'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>