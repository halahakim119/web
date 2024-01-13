<?php

include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $apartmentId = $_PUT['apartment_id'];
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
    $stmtCheckChanges = $conn->prepare("SELECT * FROM apartments WHERE apartment_id = ?");
    $stmtCheckChanges->bind_param("i", $apartmentId);
    $stmtCheckChanges->execute();
    $resultCheckChanges = $stmtCheckChanges->get_result()->fetch_assoc();
    $stmtCheckChanges->close();

    if (
        $resultCheckChanges['apartment_location'] == $newLocation &&
        $resultCheckChanges['seller_name'] == $newSellerName &&
        $resultCheckChanges['seller_phone'] == $newSellerPhone &&
        $resultCheckChanges['apartment_area'] == $newArea &&
        $resultCheckChanges['apartment_width'] == $newWidth &&
        $resultCheckChanges['apartment_length'] == $newLength &&
        $resultCheckChanges['apartment_availability'] == $newAvailability &&
        $resultCheckChanges['apartment_status'] == $newStatus &&
        $resultCheckChanges['apartment_price'] == $newPrice
    ) {
        // No changes made, consider it as a success
        $response = array('status' => 'success', 'message' => 'No changes made');
    } else {
        $stmt = $conn->prepare("UPDATE apartments SET apartment_location = ?, seller_name = ?, seller_phone = ?, apartment_area = ?, apartment_width = ?, apartment_length = ?, 
        apartment_availability = ?, apartment_price = ?, apartment_status = ? WHERE apartment_id = ?");
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
            $apartmentId
        );

        // Log the SQL query for debugging purposes
        error_log("SQL Query: " . $stmt->sqlstate);

        $stmt->execute();

        // Log the affected rows for debugging purposes
        error_log("Affected Rows: " . $stmt->affected_rows);

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Apartment updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update apartment');
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