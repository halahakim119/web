<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request for adding a user
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Username already exists');
    } else {
        // Insert a new user if the username is unique
        $stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $userType);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'User added successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to add user');
        }
    }

    // Send a proper JSON response header
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>