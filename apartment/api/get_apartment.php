<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['apartment_id'])) {
        $apartmentId = $_GET['apartment_id'];

        $stmt = $conn->prepare("SELECT * FROM apartments WHERE apartment_id = ?");
        $stmt->bind_param("i", $apartmentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $apartment = $result->fetch_assoc();
            $response = array('status' => 'success', 'apartment' => $apartment);
        } else {
            $response = array('status' => 'error', 'message' => 'Apartment not found');
        }

        echo json_encode($response);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Missing apartment_id parameter'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>