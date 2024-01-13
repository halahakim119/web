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
    $house_status = '';

    if (!empty($forSale) && empty($forRent)) {
        $house_status = 'for sell';
    } elseif (empty($forSale) && !empty($forRent)) {
        $house_status = 'for rent';
    }

    $stmt = $conn->prepare("INSERT INTO houses (house_location, house_area, house_width, house_length, house_availability, house_price, seller_name, seller_phone, house_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiisisis", $location, $area, $width, $length, $availability, $price, $sellerName, $sellerPhone, $house_status);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'House added successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add house');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>