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
    $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $checkStmt->bind_param("si", $newUsername, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Username already in use');
    } else {
        // Check if there are any changes
        $existingStmt = $conn->prepare("SELECT username, password, user_type FROM users WHERE user_id = ?");
        $existingStmt->bind_param("i", $userId);
        $existingStmt->execute();
        $existingResult = $existingStmt->get_result();

        if ($existingResult->num_rows > 0) {
            $existingUserData = $existingResult->fetch_assoc();

            if (
                $newUsername === $existingUserData['username'] &&
                $password === $existingUserData['password'] &&
                $userType === $existingUserData['user_type']
            ) {
                $response = array('status' => 'success', 'message' => 'No changes made');
            } else {
                // Update the user
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
    }

    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
?>