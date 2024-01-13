<?php

include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $landId = $_PUT['land_id'];
    $newLocation = $_PUT['location'];
    $newArea = $_PUT['area'];
    $newWidth = $_PUT['width'];
    $newLength = $_PUT['length'];
    $forSale = $_PUT['forSell'];
    $forRent = $_PUT['forRent'];
    $newPrice = $_PUT['price'];
    $newSellerName = $_PUT['seller_name'];
    $newSellerPhone = $_PUT['seller_phone'];

    // Set default availability to 'available'
    $newAvailability = 'available';

    // Set default status to empty
    $newStatus = '';

    if (!empty($forSale) && empty($forRent)) {
        $newStatus = 'for sell';
    } elseif (empty($forSale) && !empty($forRent)) {
        $newStatus = 'for rent';
    }

    // Check if there are no changes
    $stmtCheckChanges = $conn->prepare("SELECT * FROM lands WHERE land_id = ?");
    $stmtCheckChanges->bind_param("i", $landId);
    $stmtCheckChanges->execute();
    $resultCheckChanges = $stmtCheckChanges->get_result()->fetch_assoc();
    $stmtCheckChanges->close();

    if (
        $resultCheckChanges['land_location'] == $newLocation &&
        $resultCheckChanges['seller_name'] == $newSellerName &&
        $resultCheckChanges['seller_phone'] == $newSellerPhone &&
        $resultCheckChanges['land_area'] == $newArea &&
        $resultCheckChanges['land_width'] == $newWidth &&
        $resultCheckChanges['land_length'] == $newLength &&
        $resultCheckChanges['land_availability'] == $newAvailability &&
        $resultCheckChanges['land_status'] == $newStatus &&
        $resultCheckChanges['land_price'] == $newPrice
    ) {
        // No changes made, consider it as a success
        $response = array('status' => 'success', 'message' => 'No changes made');
    } else {
        $stmt = $conn->prepare("UPDATE lands SET land_location = ?, seller_name = ?, seller_phone = ?, land_area = ?, land_width = ?, land_length = ?, 
        land_availability = ?, land_price = ?, land_status = ? WHERE land_id = ?");
        $stmt->bind_param(
            "ssiiiisisi",
            $newLocation,
            $newSellerName,
            $newSellerPhone,
            $newArea,
            $newWidth,
            $newLength,
            $newAvailability,
            $newPrice,
            $newStatus,
            $landId
        );

        // Log the SQL query for debugging purposes
        error_log("SQL Query: " . $stmt->sqlstate);

        $stmt->execute();

        // Log the affected rows for debugging purposes
        error_log("Affected Rows: " . $stmt->affected_rows);

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Land updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update land');
        }
    }

    // Log the response for debugging purposes
    error_log("Response: " . json_encode($response));

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');

    // Log the response for debugging purposes
    error_log("Response: " . json_encode($response));

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>