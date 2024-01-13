<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['search_query'])) {
        $searchQuery = '%' . $_GET['search_query'] . '%';

        // Handle empty search query
        if (empty($searchQuery)) {
            $stmt = $conn->prepare("SELECT * FROM rented_and_sold");
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $stmt = $conn->prepare("SELECT * FROM rented_and_sold 
                                    WHERE buyer_name LIKE ? 
                                    OR buyer_phone LIKE ?");
            $stmt->bind_param("ss", $searchQuery, $searchQuery);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        if ($result === false) {
            $response = array('status' => 'error', 'message' => $conn->error);
        } else {
            if ($result->num_rows > 0) {
                $rentedAndSolds = array();
                while ($row = $result->fetch_assoc()) {
                    $rentedAndSolds[] = $row;
                }
                $response = array('status' => 'success', 'rentedAndSolds' => $rentedAndSolds);
            } else {
                $response = array('status' => 'error', 'message' => 'No matching found');
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Missing search_query parameter'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>