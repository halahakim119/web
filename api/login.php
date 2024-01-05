<?php
session_start();
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Handle login request
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate user credentials against the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $response = array();

        if (!$result) {
            $response['status'] = 'error';
            $response['message'] = 'Query failed: ' . $stmt->error;
        } elseif ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Debug message: Display retrieved user data
            $response['debug'] = 'User found: ' . print_r($user, true);

            // Compare the plain text password directly
            if ($password == $user['password']) {
                // Save user information in the session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                $response['status'] = 'success';
                $response['user_type'] = $user['user_type'];
                $response['message'] = 'Login successful';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Invalid credentials';
            }
        } else {
            $response['status'] = 'user_not_found';
            $response['message'] = 'User not found';
        }

        echo json_encode($response);
    } else {
        // Invalid action parameter
        echo json_encode(array('status' => 'error', 'message' => 'Invalid action parameter'));
    }
}
?>