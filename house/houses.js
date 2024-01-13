// houses.js

$(document).ready(function () {
  fetchHouses();
});

function openAddHouseModal() {
  $("#addHouseModal").show();
}

function closeAddHouseModal() {
  $("#addHouseModal").hide();
}

function addHouse() {
  var location = $("#addLocation").val();
  var area = $("#addArea").val();
  var width = $("#addWidth").val();
  var length = $("#addLength").val();
  var forSell = $("#addForSell").prop("checked") ? "for sell" : "";
  var forRent = $("#addForRent").prop("checked") ? "for rent" : "";
  var price = $("#addPrice").val();
  var sellerName = $("#addSellerName").val();
  var sellerPhone = $("#addSellerPhone").val();

  $.ajax({
    type: "POST",
    url: "api/add_house.php",
    data: {
      location: location,
      area: area,
      width: width,
      length: length,
      forSell: forSell,
      forRent: forRent,
      price: price,
      sellerName: sellerName,
      sellerPhone: sellerPhone,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("House added successfully");
        closeAddHouseModal();
        fetchHouses();
      } else {
        alert("Error adding house: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error adding house. Status: " + status + ", Error: " + error);
    },
  });
}

function fetchHouses() {
  $.ajax({
    type: "GET",
    url: "api/get_houses.php", // Adjust the API URL accordingly
    dataType: "json",
    success: function (response) {
      updateHouseTable(response);
    },
    error: function (xhr, status, error) {
      alert("Error fetching houses. Status: " + status + ", Error: " + error);
    },
  });
}

function updateHouseTable(houses) {
  var tableBody = $("#houseTableBody");
  tableBody.empty();

  for (var i = 0; i < houses.length; i++) {
    var house = houses[i];
    var availabilityButtons = "";

    if (house.house_status === "for sell") {
      availabilityButtons +=
        "<button class='sell-button' onclick='openSellModal(" +
        house.house_id +
        ")'>Sell</button>";
    } else if (house.house_status === "for rent") {
      availabilityButtons +=
        "<button class='rent-button' onclick='openRentModal(" +
        house.house_id +
        ")'>Rent</button>";
    }

    var row =
      "<tr" +
      (house.house_availability == "not available"
        ? " style='background-color: pink;'"
        : "") +
      ">" +
      "<td>" +
      house.house_id +
      "</td>" +
      "<td>" +
      house.house_location +
      "</td>" +
      "<td>" +
      house.house_area +
      "</td>" +
      "<td>" +
      house.house_width +
      "</td>" +
      "<td>" +
      house.house_length +
      "</td>" +
      "<td>" +
      house.house_availability +
      "</td>" +
      "<td>" +
      house.house_price +
      "</td>" +
      "<td>" +
      house.seller_name +
      "</td>" +
      "<td>" +
      house.seller_phone +
      "</td>" +
      "<td>" +
      availabilityButtons +
      "<button class='edit-button' onclick='openEditHouseModal(" +
      house.house_id +
      ")'>Edit</button>" +
      "<button class='delete-button' onclick='deleteHouse(" +
      house.house_id +
      ")'>Delete</button>" +
      "</td>" +
      "</tr>";

    tableBody.append(row);
  }
}

function deleteHouse(houseId) {
  // Ask for confirmation
  var confirmDelete = window.confirm(
    "Are you sure you want to delete this house?"
  );

  if (confirmDelete) {
    // Proceed with deletion
    $.ajax({
      type: "DELETE",
      url: "api/delete_house.php",
      data: { house_id: houseId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert("House deleted successfully");
          fetchHouses();
        } else {
          alert("Error deleting house: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        alert("Error deleting house. Status: " + status + ", Error: " + error);
      },
    });
  }
}

function editHouse() {
  var houseId = $("#editHouseId").val();
  var location = $("#editLocation").val();
  var area = $("#editArea").val();
  var width = $("#editWidth").val();
  var length = $("#editLength").val();

  var forSell = $("#editForSell").prop("checked") ? "for sell" : "";
  var forRent = $("#editForRent").prop("checked") ? "for rent" : "";

  var price = $("#editPrice").val();
  var sellerName = $("#editSellerName").val();
  var sellerPhone = $("#editSellerPhone").val();

  console.log("Data to be sent:", {
    house_id: houseId,
    location: location,
    area: area,
    width: width,
    length: length,
    forSell: forSell,
    forRent: forRent,
    price: price,
    seller_name: sellerName,
    seller_phone: sellerPhone,
  });

  $.ajax({
    type: "PUT",
    url: "api/edit_house.php",
    data: {
      house_id: houseId,
      location: location,
      area: area,
      width: width,
      length: length,
      forSell: forSell,
      forRent: forRent,
      price: price,
      seller_name: sellerName,
      seller_phone: sellerPhone,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("House updated successfully");
        closeEditHouseModal();
        fetchHouses();
      } else {
        alert("Error updating house: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error updating house. Status: " + status + ", Error: " + error);
    },
  });
}

function openEditHouseModal(houseId) {
  // Fetch the house details for editing
  $.ajax({
    type: "GET",
    url: "api/get_house.php",
    data: { house_id: houseId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        var house = response.house;

        // Populate the edit modal fields with house details
        $("#editHouseId").val(house.house_id);
        $("#editLocation").val(house.house_location);
        $("#editArea").val(house.house_area);
        $("#editWidth").val(house.house_width);
        $("#editLength").val(house.house_length);
        $("#editSellerName").val(house.seller_name);
        $("#editSellerPhone").val(house.seller_phone);

        $("#editForSell").prop(
          "checked",
          house.house_availability === "for sell"
        );
        $("#editForRent").prop(
          "checked",
          house.house_availability === "for rent"
        );
        $("#editPrice").val(house.house_price);

        // Show the edit modal
        $("#editHouseModal").show();
      } else {
        alert("Error fetching house details: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching house details. Status: " + status + ", Error: " + error
      );
    },
  });
}

function closeEditHouseModal() {
  $("#editHouseModal").hide();
}

function searchHouses() {
  var searchQuery = document.getElementById("searchQuery").value;

  $.ajax({
    type: "GET",
    url: "api/search_house.php",
    data: { search_query: searchQuery },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        updateHouseTable(response.houses);
      } else {
        alert(response.message);
      }
    },
    error: function () {
      alert("Error during search. Please try again.");
    },
  });
}

// Add the following functions

function openSellModal(houseId) {
  $("#sellModal").show();
  $("#sellHouseId").val(houseId);
}

function closeSellModal() {
  $("#sellModal").hide();
  // Clear input fields on modal close
  $("#buyerName").val("");
  $("#buyerPhone").val("");
  $("#paymentStatusSell").val("paid");
}

function sellHouse() {
  var houseId = $("#sellHouseId").val();
  var buyerName = $("#buyerName").val();
  var buyerPhone = $("#buyerPhone").val();
  var paymentStatus = $("#paymentStatusSell").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      house_id: houseId,
      buyer_name: buyerName,
      buyer_phone: buyerPhone,
      payment_status: paymentStatus,
      house_availability: "sold",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("House sold successfully");
        closeSellModal();
        fetchHouses();
      } else {
        alert("Error selling house: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error selling house. Status: " + status + ", Error: " + error);
    },
  });
}

function openRentModal(houseId) {
  $("#rentModal").show();
  $("#rentHouseId").val(houseId);
}
function closeRentModal() {
  $("#rentModal").hide();
  // Clear input fields on modal close
  $("#tenantName").val("");
  $("#tenantPhone").val("");
  $("#paymentStatusRent").val("paid");
}

function rentHouse() {
  var houseId = $("#rentHouseId").val();
  var tenantName = $("#tenantName").val();
  var tenantPhone = $("#tenantPhone").val();
  var paymentStatus = $("#paymentStatusRent").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      house_id: houseId,
      buyer_name: tenantName,
      buyer_phone: tenantPhone,
      payment_status: paymentStatus,
      house_availability: "rented",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("House rented successfully");
        closeRentModal();
        fetchHouses();
      } else {
        alert("Error renting house: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error renting house. Status: " + status + ", Error: " + error);
    },
  });
}
