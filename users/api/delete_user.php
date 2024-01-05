<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);

    // Handle DELETE request for deleting a user
    $userId = $_DELETE['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array('status' => 'success', 'message' => 'User deleted successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete user');
    }

    // Send a proper JSON response header
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>