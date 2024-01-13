$(document).ready(function () {
  fetchRentedAndSolds();
});

function fetchRentedAndSolds() {
  $.ajax({
    type: "GET",
    url: "api/get_rentedAndSolds.php", // Adjust the API URL accordingly
    dataType: "json",
    success: function (response) {
      updateRentedAndSoldTable(response);
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching rentedAndSolds. Status: " + status + ", Error: " + error
      );
    },
  });
}

function updateRentedAndSoldTable(rentedAndSolds) {
  var tableBody = $("#rentedAndSoldTableBody");
  tableBody.empty();

  for (var i = 0; i < rentedAndSolds.length; i++) {
    var rentedAndSold = rentedAndSolds[i];

    var row =
      "<tr>" +
      "<td>" +
      rentedAndSold.id +
      "</td>" +
      "<td>" +
      rentedAndSold.property_id +
      "</td>" +
      "<td>" +
      rentedAndSold.property_type +
      "</td>" +
      "<td>" +
      rentedAndSold.buyer_name +
      "</td>" +
      "<td>" +
      rentedAndSold.buyer_phone +
      "</td>" +
      "<td>" +
      rentedAndSold.payment_status +
      "</td>" +
      "<td>" +
      rentedAndSold.property_availability +
      "</td>" +
      "<td>" +
      "<button class='delete-button' onclick='deleteRentedAndSold(" +
      rentedAndSold.id +
      ")'>Delete</button>" +
      "</td>" +
      "</tr>";

    tableBody.append(row);
  }
}

function deleteRentedAndSold(rentedAndSoldId) {
  // Ask for confirmation
  var confirmDelete = window.confirm(
    "Are you sure you want to delete this property?"
  );

  if (confirmDelete) {
    // Proceed with deletion
    $.ajax({
      type: "DELETE",
      url: "api/delete_rentedAndSold.php",
      data: { rentedAndSold_id: rentedAndSoldId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert(" deleted successfully");
          fetchRentedAndSolds();
        } else {
          alert("Error deleting : " + response.message);
        }
      },
      error: function (xhr, status, error) {
        alert("Error deleting . Status: " + status + ", Error: " + error);
      },
    });
  }
}

function searchRentedAndSolds() {
  var searchQuery = document.getElementById("searchQuery").value;

  $.ajax({
    type: "GET",
    url: "api/search_rentedAndSold.php",
    data: { search_query: searchQuery },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        updateRentedAndSoldTable(response.rentedAndSolds);
      } else {
        alert(response.message);
      }
    },
    error: function () {
      alert("Error during search. Please try again.");
    },
  });
}
