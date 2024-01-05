<!-- manage_users.php -->

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>

    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="styles.css">

    <!-- Include any necessary scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="users.js"></script>
</head>

<body>
    <header>
        <button class="back-button" onclick="goBack()">Back</button>
        <span class="title">Manage Users</span>

    </header>
    <div class="container">
        <!-- Table to display users -->
        <table border="1">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <!-- Users will be displayed here dynamically using JavaScript -->
            </tbody>
        </table>
        <!-- Updated pagination HTML -->
        <div id="pagination">
            <button onclick="prevPage()">Previous</button>
            <span id="currentPage">1</span>
            <span>/</span>
            <span id="totalPages">1</span>
            <button onclick="nextPage()">Next</button>
        </div>



        <!-- Add User button -->
        <button class="add-button" onclick="openAddUserModal()">Add User</button>

    </div>

    <!-- Add User Modal (hidden by default) -->
    <div id="addUserModal" style="display: none;">
        <h2>Add User</h2>
        <label for="addUsername">Username:</label>
        <input type="text" id="addUsername" required>

        <label for="addPassword">Password:</label>
        <input type="password" id="addPassword" required>

        <label for="addUserType">User Type:</label>
        <select id="addUserType">
            <option value="admin">Admin</option>
            <option value="employee">Employee</option>
        </select>

        <button onclick="addUser()">Save</button>
        <button onclick="closeAddUserModal()">Cancel</button>
    </div>

    <!-- Edit User Modal (hidden by default) -->
    <div id="editUserModal" style="display: none;">
        <h2>Edit User</h2>
        <label for="editUserId">User ID:</label>
        <input type="text" id="editUserId" readonly>

        <label for="editUsername">Username:</label>
        <input type="text" id="editUsername" required>

        <label for="editPassword">Password:</label>
        <input type="password" id="editPassword" required>

        <label for="editUserType">User Type:</label>
        <select id="editUserType">
            <option value="admin">Admin</option>
            <option value="employee">Employee</option>
        </select>

        <button onclick="saveEditedUser()">Save</button>
        <button onclick="closeEditUserModal()">Cancel</button>
    </div>

    <script src="users.js"></script>
    <script>
        function goBack() {
            // Use the history object to navigate back
            window.history.back();
        }
    </script>
</body>

</html>