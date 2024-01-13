<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $area = $_POST['area'];
    $width = $_POST['width'];
    $length = $_POST['length'];
    $forSale = $_POST['forSell'];
    $forRent = $_POST['forRent'];

    $price = $_POST['price'];
    $sellerName = $_POST['sellerName'];
    $sellerPhone = $_POST['sellerPhone'];

    // Set default availability to 'available'
    $availability = 'available';

    // Set default status to empty
    $apartment_status = '';

    if (!empty($forSale) && empty($forRent)) {
        $apartment_status = 'for sell';
    } elseif (empty($forSale) && !empty($forRent)) {
        $apartment_status = 'for rent';
    }

    $stmt = $conn->prepare("INSERT INTO apartments (apartment_location, apartment_area, apartment_width, apartment_length, apartment_availability, apartment_price, seller_name, seller_phone, apartment_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiisisis", $location, $area, $width, $length, $availability, $price, $sellerName, $sellerPhone, $apartment_status);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'Apartment added successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add apartment');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>