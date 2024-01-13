<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['search_query'])) {
        $searchQuery = '%' . $_GET['search_query'] . '%';

        $stmt = $conn->prepare("SELECT * FROM houses 
                                WHERE house_location LIKE ? 
                                OR seller_name LIKE ? ");
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            $response = array('status' => 'error', 'message' => $conn->error);
        } else {
            if ($result->num_rows > 0) {
                $houses = array();
                while ($row = $result->fetch_assoc()) {
                    $houses[] = $row;
                }
                $response = array('status' => 'success', 'houses' => $houses);
            } else {
                $response = array('status' => 'error', 'message' => 'No matching houses found');
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