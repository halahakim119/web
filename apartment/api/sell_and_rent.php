<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apartmentId = $_POST['apartment_id'];
    $buyerName = $_POST['buyer_name'];
    $buyerPhone = $_POST['buyer_phone'];
    $paymentStatus = $_POST['payment_status'];
    $apartmentAvailability = $_POST['apartment_availability'];
    $property_type = 'apartment';

    // Prepare the statements
    $stmtRentedAndSold = $conn->prepare("INSERT INTO rented_and_sold (property_id, buyer_name, buyer_phone, payment_status, property_type, property_availability) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtRentedAndSold->bind_param("isisss", $apartmentId, $buyerName, $buyerPhone, $paymentStatus, $property_type, $apartmentAvailability);

    $stmtApartments = $conn->prepare("UPDATE apartments SET apartment_availability = 'not available' WHERE apartment_id = ?");
    $stmtApartments->bind_param("i", $apartmentId);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Execute the first query
        $stmtRentedAndSold->execute();

        // Execute the second query
        $stmtApartments->execute();

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
        $stmtApartments->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>