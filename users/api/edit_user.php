<?php
include '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    // Handle PUT request for updating a user
    $userId = $_PUT['user_id'];
    $newUsername = $_PUT['username'];
    $password = $_PUT['password'];
    $userType = $_PUT['user_type'];

    // Check if the new username is already in use by other users
    $checkStmt = $conn->prepare("SELECT user_id, username, password, user_type FROM users WHERE user_id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $existingUserData = $checkResult->fetch_assoc();

        // Check if there are any changes
        if (
            $newUsername === $existingUserData['username'] &&
            $password === $existingUserData['password'] &&
            $userType === $existingUserData['user_type']
        ) {
            $response = array('status' => 'success', 'message' => 'No changes made');
        } else {
            // Update the user if there are changes
            $updateStmt = $conn->prepare("UPDATE users SET username = ?, password = ?, user_type = ? WHERE user_id = ?");
            $updateStmt->bind_param("sssi", $newUsername, $password, $userType, $userId);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                $response = array('status' => 'success', 'message' => 'User updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update user');
            }
        }
    } else {
        $response = array('status' => 'error', 'message' => 'User not found');
    }

    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
?>