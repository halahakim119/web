// Function to fetch and display users on page load
$(document).ready(function () {
  fetchUsers();
});

// Function to open the Add User modal
function openAddUserModal() {
  $("#addUserModal").show();
}

// Function to close the Add User modal
function closeAddUserModal() {
  $("#addUserModal").hide();
}

// Function to close the Edit User modal
function closeEditUserModal() {
  $("#editUserModal").hide();
}

// Function to add a new user
function addUser() {
  var username = $("#addUsername").val();
  var password = $("#addPassword").val();
  var userType = $("#addUserType").val();

  $.ajax({
    type: "POST",
    url: "api/add_user.php", // Adjust the API URL accordingly
    data: {
      username: username,
      password: password,
      user_type: userType,
    },
    dataType: "json",
    success: function (response) {
      // Check if the user was successfully added
      if (response.status === "success") {
        alert("User added successfully");
        // Close the modal and fetch and display users again
        closeAddUserModal();
        fetchUsers();
      } else {
        alert("Error adding user: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error adding user. Status: " + status + ", Error: " + error);
    },
  });
}

// Function to open the Edit User modal
function openEditUserModal(userId) {
  // Fetch user details using userId and populate the edit modal form
  $.ajax({
    type: "GET",
    url: "api/get_user.php", // Adjust the API URL accordingly
    data: {
      user_id: userId,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        // Populate the edit modal form with user details
        $("#editUserId").val(response.user.user_id);
        $("#editUsername").val(response.user.username);
        $("#editPassword").val(response.user.password);
        $("#editUserType").val(response.user.user_type);

        // Show the edit modal
        $("#editUserModal").show();
      } else {
        alert("Error fetching user details: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching user details. Status: " + status + ", Error: " + error
      );
    },
  });
}

// Function to delete a user
function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    // Implement the logic to delete the user using Ajax
    $.ajax({
      type: "DELETE", // Change to DELETE method
      url: "api/delete_user.php",
      data: {
        user_id: userId,
      },
      dataType: "json",
      success: function (response) {
        if (response && response.status === "success") {
          alert("User deleted successfully");
          // Fetch and display users again
          fetchUsers();
        } else {
          alert(
            "Error deleting user: " +
              (response ? response.message : "Unknown error")
          );
        }
      },
      error: function (xhr, status, error) {
        alert("Error deleting user. Status: " + status + ", Error: " + error);
      },
    });
  }
}

// Function to save edited user details
function saveEditedUser() {
  var userId = $("#editUserId").val();
  var username = $("#editUsername").val();
  var password = $("#editPassword").val();
  var userType = $("#editUserType").val();

  // Make an AJAX request to update user details
  $.ajax({
    type: "PUT", // Change to PUT method
    url: "api/edit_user.php", // Adjust the API URL accordingly
    data: {
      user_id: userId,
      username: username,
      password: password,
      user_type: userType,
    },
    dataType: "json",
    success: function (response) {
      if (response && response.status === "success") {
        alert("User details updated successfully");
        // Hide the edit modal and fetch and display users again
        closeEditUserModal();
        fetchUsers();
      } else {
        alert(
          "Error updating user details: " +
            (response ? response.message : "Unknown error")
        );
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error updating user details. Status: " + status + ", Error: " + error
      );
    },
  });
}
// Declare variables for pagination
var currentPage = 1;
var usersPerPage = 10;

// Function to fetch and display users
function fetchUsers() {
  // Make an Ajax request to fetch users from the API
  $.ajax({
    type: "GET",
    url: "api/get_users.php", // Adjust the API URL accordingly
    dataType: "json",
    success: function (response) {
      // Handle the response and dynamically update the user table
      updateTable(response);
    },
    error: function (xhr, status, error) {
      alert("Error fetching users. Status: " + status + ", Error: " + error);
    },
  });
}

// Function to dynamically update the user table with pagination
function updateTable(users) {
  var tableBody = $("#userTableBody");
  tableBody.empty();

  // Calculate the start and end indices for the current page
  var startIndex = (currentPage - 1) * usersPerPage;
  var endIndex = startIndex + usersPerPage;

  for (var i = startIndex; i < endIndex && i < users.length; i++) {
    var user = users[i];
    var row =
      "<tr>" +
      "<td>" +
      user.user_id +
      "</td>" +
      "<td>" +
      user.username +
      "</td>" +
      "<td>" +
      user.password +
      "</td>" +
      "<td>" +
      user.user_type +
      "</td>" +
      "<td>" +
      "<button class='edit-button' onclick='openEditUserModal(" +
      user.user_id +
      ")'>Edit</button>" +
      "<button class='delete-button' onclick='deleteUser(" +
      user.user_id +
      ")'>Delete</button>" +
      "</td>" +
      "</tr>";

    tableBody.append(row);
  }

  // Update the current page and total pages indicators
  $("#currentPage").text(currentPage);
  $("#totalPages").text(Math.ceil(users.length / usersPerPage));
}

// Function to go to the next page
function nextPage() {
  currentPage++;
  fetchUsers(); // Fetch and display users for the next page
}

// Function to go to the previous page
function prevPage() {
  if (currentPage > 1) {
    currentPage--;
    fetchUsers(); // Fetch and display users for the previous page
  }
}
