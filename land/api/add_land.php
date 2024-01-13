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
    $land_status = '';

    if (!empty($forSale) && empty($forRent)) {
        $land_status = 'for sell';
    } elseif (empty($forSale) && !empty($forRent)) {
        $land_status = 'for rent';
    }

    $stmt = $conn->prepare("INSERT INTO lands (land_location, land_area, land_width, land_length, land_availability, land_price, seller_name, seller_phone, land_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiisisis", $location, $area, $width, $length, $availability, $price, $sellerName, $sellerPhone, $land_status);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'Land added successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add land');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>