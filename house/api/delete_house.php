<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);

    $houseId = $_DELETE['house_id'];

    // Fetch property type and property id before deleting the house
    $fetchStmt = $conn->prepare("SELECT property_type, property_id FROM rented_and_sold WHERE property_type = 'house' AND property_id = ?");
    $fetchStmt->bind_param("i", $houseId);
    $fetchStmt->execute();
    $fetchStmt->bind_result($propertyType, $propertyId);
    $fetchStmt->fetch();
    $fetchStmt->close();

    $deleteStmt = $conn->prepare("DELETE FROM houses WHERE house_id = ?");
    $deleteStmt->bind_param("i", $houseId);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'House deleted successfully');

        // If the house was found in rented_and_sold, delete it from there as well
        if ($propertyType === 'house' && $propertyId == $houseId) {
            $deleteRentedAndSoldStmt = $conn->prepare("DELETE FROM rented_and_sold WHERE property_type = 'house' AND property_id = ?");
            $deleteRentedAndSoldStmt->bind_param("i", $houseId);
            $deleteRentedAndSoldStmt->execute();
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete house');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>