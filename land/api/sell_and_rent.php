<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $landId = $_POST['land_id'];
    $buyerName = $_POST['buyer_name'];
    $buyerPhone = $_POST['buyer_phone'];
    $paymentStatus = $_POST['payment_status'];
    $landAvailability = $_POST['land_availability'];
    $property_type = 'land';

    // Prepare the statements
    $stmtRentedAndSold = $conn->prepare("INSERT INTO rented_and_sold (property_id, buyer_name, buyer_phone, payment_status, property_type, property_availability) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtRentedAndSold->bind_param("isisss", $landId, $buyerName, $buyerPhone, $paymentStatus, $property_type, $landAvailability);

    $stmtLands = $conn->prepare("UPDATE lands SET land_availability = 'not available' WHERE land_id = ?");
    $stmtLands->bind_param("i", $landId);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Execute the first query
        $stmtRentedAndSold->execute();

        // Execute the second query
        $stmtLands->execute();

        // Commit the transaction
        $conn->commit();

        $response = array('status' => 'success');
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();

        $response = array('status' => 'error', 'message' => $e->getMessage());
    } finally {
        // Close the prepared statements
        $stmtRentedAndSold->close();
        $stmtLands->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>