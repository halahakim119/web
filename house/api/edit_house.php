<?php

include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $houseId = $_PUT['house_id'];
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
    $stmtCheckChanges = $conn->prepare("SELECT * FROM houses WHERE house_id = ?");
    $stmtCheckChanges->bind_param("i", $houseId);
    $stmtCheckChanges->execute();
    $resultCheckChanges = $stmtCheckChanges->get_result()->fetch_assoc();
    $stmtCheckChanges->close();

    if (
        $resultCheckChanges['house_location'] == $newLocation &&
        $resultCheckChanges['seller_name'] == $newSellerName &&
        $resultCheckChanges['seller_phone'] == $newSellerPhone &&
        $resultCheckChanges['house_area'] == $newArea &&
        $resultCheckChanges['house_width'] == $newWidth &&
        $resultCheckChanges['house_length'] == $newLength &&
        $resultCheckChanges['house_availability'] == $newAvailability &&
        $resultCheckChanges['status'] == $newStatus &&
        $resultCheckChanges['house_price'] == $newPrice
    ) {
        // No changes made, consider it as a success
        $response = array('status' => 'success', 'message' => 'No changes made');
    } else {
        $stmt = $conn->prepare("UPDATE houses SET house_location = ?, seller_name = ?, seller_phone = ?, house_area = ?, house_width = ?, house_length = ?, 
        house_availability = ?, house_price = ?, status = ? WHERE house_id = ?");
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
            $houseId
        );

        // Log the SQL query for debugging purposes
        error_log("SQL Query: " . $stmt->sqlstate);

        $stmt->execute();

        // Log the affected rows for debugging purposes
        error_log("Affected Rows: " . $stmt->affected_rows);

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'House updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update house');
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