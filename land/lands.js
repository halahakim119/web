// lands.js

$(document).ready(function () {
  fetchLands();
});

function openAddLandModal() {
  $("#addLandModal").show();
}

function closeAddLandModal() {
  $("#addLandModal").hide();
}

function addLand() {
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
    url: "api/add_land.php",
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
        alert("Land added successfully");
        closeAddLandModal();
        fetchLands();
      } else {
        alert("Error adding land: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error adding land. Status: " + status + ", Error: " + error);
    },
  });
}

function fetchLands() {
  $.ajax({
    type: "GET",
    url: "api/get_lands.php", // Adjust the API URL accordingly
    dataType: "json",
    success: function (response) {
      updateLandTable(response);
    },
    error: function (xhr, status, error) {
      alert("Error fetching lands. Status: " + status + ", Error: " + error);
    },
  });
}

function updateLandTable(lands) {
  var tableBody = $("#landTableBody");
  tableBody.empty();

  for (var i = 0; i < lands.length; i++) {
    var land = lands[i];
    var availabilityButtons = "";

    if (land.land_status === "for sell") {
      availabilityButtons +=
        "<button class='sell-button' onclick='openSellModal(" +
        land.land_id +
        ")'>Sell</button>";
    } else if (land.land_status === "for rent") {
      availabilityButtons +=
        "<button class='rent-button' onclick='openRentModal(" +
        land.land_id +
        ")'>Rent</button>";
    }

    var row =
      "<tr" +
      (land.land_availability == "not available"
        ? " style='background-color: pink;'"
        : "") +
      ">" +
      "<td>" +
      land.land_id +
      "</td>" +
      "<td>" +
      land.land_location +
      "</td>" +
      "<td>" +
      land.land_area +
      "</td>" +
      "<td>" +
      land.land_width +
      "</td>" +
      "<td>" +
      land.land_length +
      "</td>" +
      "<td>" +
      land.land_availability +
      "</td>" +
      "<td>" +
      land.land_price +
      "</td>" +
      "<td>" +
      land.seller_name +
      "</td>" +
      "<td>" +
      land.seller_phone +
      "</td>" +
      "<td>" +
      availabilityButtons +
      "<button class='edit-button' onclick='openEditLandModal(" +
      land.land_id +
      ")'>Edit</button>" +
      "<button class='delete-button' onclick='deleteLand(" +
      land.land_id +
      ")'>Delete</button>" +
      "</td>" +
      "</tr>";

    tableBody.append(row);
  }
}

function deleteLand(landId) {
  // Ask for confirmation
  var confirmDelete = window.confirm(
    "Are you sure you want to delete this land?"
  );

  if (confirmDelete) {
    // Proceed with deletion
    $.ajax({
      type: "DELETE",
      url: "api/delete_land.php",
      data: { land_id: landId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert("Land deleted successfully");
          fetchLands();
        } else {
          alert("Error deleting land: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        alert("Error deleting land. Status: " + status + ", Error: " + error);
      },
    });
  }
}

function editLand() {
  var landId = $("#editLandId").val();
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
    land_id: landId,
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
    url: "api/edit_land.php",
    data: {
      land_id: landId,
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
        alert("Land updated successfully");
        closeEditLandModal();
        fetchLands();
      } else {
        alert("Error updating land: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error updating land. Status: " + status + ", Error: " + error);
    },
  });
}

function openEditLandModal(landId) {
  // Fetch the land details for editing
  $.ajax({
    type: "GET",
    url: "api/get_land.php",
    data: { land_id: landId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        var land = response.land;

        // Populate the edit modal fields with land details
        $("#editLandId").val(land.land_id);
        $("#editLocation").val(land.land_location);
        $("#editArea").val(land.land_area);
        $("#editWidth").val(land.land_width);
        $("#editLength").val(land.land_length);
        $("#editSellerName").val(land.seller_name);
        $("#editSellerPhone").val(land.seller_phone);

        $("#editForSell").prop("checked", land.land_status === "for sell");
        $("#editForRent").prop("checked", land.land_status === "for rent");
        $("#editPrice").val(land.land_price);

        // Show the edit modal
        $("#editLandModal").show();
      } else {
        alert("Error fetching land details: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching land details. Status: " + status + ", Error: " + error
      );
    },
  });
}

function closeEditLandModal() {
  $("#editLandModal").hide();
}

function searchLands() {
  var searchQuery = document.getElementById("searchQuery").value;

  $.ajax({
    type: "GET",
    url: "api/search_land.php",
    data: { search_query: searchQuery },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        updateLandTable(response.lands);
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

function openSellModal(landId) {
  $("#sellModal").show();
  $("#sellLandId").val(landId);
}

function closeSellModal() {
  $("#sellModal").hide();
  // Clear input fields on modal close
  $("#buyerName").val("");
  $("#buyerPhone").val("");
  $("#paymentStatusSell").val("paid");
}

function sellLand() {
  var landId = $("#sellLandId").val();
  var buyerName = $("#buyerName").val();
  var buyerPhone = $("#buyerPhone").val();
  var paymentStatus = $("#paymentStatusSell").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      land_id: landId,
      buyer_name: buyerName,
      buyer_phone: buyerPhone,
      payment_status: paymentStatus,
      land_availability: "sold",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Land sold successfully");
        closeSellModal();
        fetchLands();
      } else {
        alert("Error selling land: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error selling land. Status: " + status + ", Error: " + error);
    },
  });
}

function openRentModal(landId) {
  $("#rentModal").show();
  $("#rentLandId").val(landId);
}
function closeRentModal() {
  $("#rentModal").hide();
  // Clear input fields on modal close
  $("#tenantName").val("");
  $("#tenantPhone").val("");
  $("#paymentStatusRent").val("paid");
}

function rentLand() {
  var landId = $("#rentLandId").val();
  var tenantName = $("#tenantName").val();
  var tenantPhone = $("#tenantPhone").val();
  var paymentStatus = $("#paymentStatusRent").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      land_id: landId,
      buyer_name: tenantName,
      buyer_phone: tenantPhone,
      payment_status: paymentStatus,
      land_availability: "rented",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Land rented successfully");
        closeRentModal();
        fetchLands();
      } else {
        alert("Error renting land: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error renting land. Status: " + status + ", Error: " + error);
    },
  });
}
