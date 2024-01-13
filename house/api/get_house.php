<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['house_id'])) {
        $houseId = $_GET['house_id'];

        $stmt = $conn->prepare("SELECT * FROM houses WHERE house_id = ?");
        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $house = $result->fetch_assoc();
            $response = array('status' => 'success', 'house' => $house);
        } else {
            $response = array('status' => 'error', 'message' => 'House not found');
        }

        echo json_encode($response);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Missing house_id parameter'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>