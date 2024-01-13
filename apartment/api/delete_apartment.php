<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);

    $apartmentId = $_DELETE['apartment_id'];

    // Fetch property type and property id before deleting the apartment
    $fetchStmt = $conn->prepare("SELECT property_type, property_id FROM rented_and_sold WHERE property_type = 'apartment' AND property_id = ?");
    $fetchStmt->bind_param("i", $apartmentId);
    $fetchStmt->execute();
    $fetchStmt->bind_result($propertyType, $propertyId);
    $fetchStmt->fetch();
    $fetchStmt->close();

    $deleteStmt = $conn->prepare("DELETE FROM apartments WHERE apartment_id = ?");
    $deleteStmt->bind_param("i", $apartmentId);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'Apartment deleted successfully');

        // If the apartment was found in rented_and_sold, delete it from there as well
        if ($propertyType === 'apartment' && $propertyId == $apartmentId) {
            $deleteRentedAndSoldStmt = $conn->prepare("DELETE FROM rented_and_sold WHERE property_type = 'apartment' AND property_id = ?");
            $deleteRentedAndSoldStmt->bind_param("i", $apartmentId);
            $deleteRentedAndSoldStmt->execute();
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete apartment');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>