// apartments.js

$(document).ready(function () {
  fetchApartments();
});

function openAddApartmentModal() {
  $("#addApartmentModal").show();
}

function closeAddApartmentModal() {
  $("#addApartmentModal").hide();
}

function addApartment() {
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
    url: "api/add_apartment.php",
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
        alert("Apartment added successfully");
        closeAddApartmentModal();
        fetchApartments();
      } else {
        alert("Error adding apartment: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error adding apartment. Status: " + status + ", Error: " + error);
    },
  });
}

function fetchApartments() {
  $.ajax({
    type: "GET",
    url: "api/get_apartments.php", // Adjust the API URL accordingly
    dataType: "json",
    success: function (response) {
      updateApartmentTable(response);
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching apartments. Status: " + status + ", Error: " + error
      );
    },
  });
}

function updateApartmentTable(apartments) {
  var tableBody = $("#apartmentTableBody");
  tableBody.empty();

  for (var i = 0; i < apartments.length; i++) {
    var apartment = apartments[i];
    var availabilityButtons = "";

    if (apartment.apartment_status === "for sell") {
      availabilityButtons +=
        "<button class='sell-button' onclick='openSellModal(" +
        apartment.apartment_id +
        ")'>Sell</button>";
    } else if (apartment.apartment_status === "for rent") {
      availabilityButtons +=
        "<button class='rent-button' onclick='openRentModal(" +
        apartment.apartment_id +
        ")'>Rent</button>";
    }

    var row =
      "<tr" +
      (apartment.apartment_availability == "not available"
        ? " style='background-color: pink;'"
        : "") +
      ">" +
      "<td>" +
      apartment.apartment_id +
      "</td>" +
      "<td>" +
      apartment.apartment_location +
      "</td>" +
      "<td>" +
      apartment.apartment_area +
      "</td>" +
      "<td>" +
      apartment.apartment_width +
      "</td>" +
      "<td>" +
      apartment.apartment_length +
      "</td>" +
      "<td>" +
      apartment.apartment_availability +
      "</td>" +
      "<td>" +
      apartment.apartment_price +
      "</td>" +
      "<td>" +
      apartment.seller_name +
      "</td>" +
      "<td>" +
      apartment.seller_phone +
      "</td>" +
      "<td>" +
      availabilityButtons +
      "<button class='edit-button' onclick='openEditApartmentModal(" +
      apartment.apartment_id +
      ")'>Edit</button>" +
      "<button class='delete-button' onclick='deleteApartment(" +
      apartment.apartment_id +
      ")'>Delete</button>" +
      "</td>" +
      "</tr>";

    tableBody.append(row);
  }
}

function deleteApartment(apartmentId) {
  // Ask for confirmation
  var confirmDelete = window.confirm(
    "Are you sure you want to delete this apartment?"
  );

  if (confirmDelete) {
    // Proceed with deletion
    $.ajax({
      type: "DELETE",
      url: "api/delete_apartment.php",
      data: { apartment_id: apartmentId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert("Apartment deleted successfully");
          fetchApartments();
        } else {
          alert("Error deleting apartment: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        alert(
          "Error deleting apartment. Status: " + status + ", Error: " + error
        );
      },
    });
  }
}

function editApartment() {
  var apartmentId = $("#editApartmentId").val();
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
    apartment_id: apartmentId,
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
    url: "api/edit_apartment.php",
    data: {
      apartment_id: apartmentId,
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
        alert("Apartment updated successfully");
        closeEditApartmentModal();
        fetchApartments();
      } else {
        alert("Error updating apartment: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error updating apartment. Status: " + status + ", Error: " + error
      );
    },
  });
}

function openEditApartmentModal(apartmentId) {
  // Fetch the apartment details for editing
  $.ajax({
    type: "GET",
    url: "api/get_apartment.php",
    data: { apartment_id: apartmentId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        var apartment = response.apartment;

        // Populate the edit modal fields with apartment details
        $("#editApartmentId").val(apartment.apartment_id);
        $("#editLocation").val(apartment.apartment_location);
        $("#editArea").val(apartment.apartment_area);
        $("#editWidth").val(apartment.apartment_width);
        $("#editLength").val(apartment.apartment_length);
        $("#editSellerName").val(apartment.seller_name);
        $("#editSellerPhone").val(apartment.seller_phone);

        $("#editForSell").prop(
          "checked",
          apartment.apartment_status === "for sell"
        );
        $("#editForRent").prop(
          "checked",
          apartment.apartment_status === "for rent"
        );
        $("#editPrice").val(apartment.apartment_price);

        // Show the edit modal
        $("#editApartmentModal").show();
      } else {
        alert("Error fetching apartment details: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert(
        "Error fetching apartment details. Status: " +
          status +
          ", Error: " +
          error
      );
    },
  });
}

function closeEditApartmentModal() {
  $("#editApartmentModal").hide();
}

function searchApartments() {
  var searchQuery = document.getElementById("searchQuery").value;

  $.ajax({
    type: "GET",
    url: "api/search_apartment.php",
    data: { search_query: searchQuery },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        updateApartmentTable(response.apartments);
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

function openSellModal(apartmentId) {
  $("#sellModal").show();
  $("#sellApartmentId").val(apartmentId);
}

function closeSellModal() {
  $("#sellModal").hide();
  // Clear input fields on modal close
  $("#buyerName").val("");
  $("#buyerPhone").val("");
  $("#paymentStatusSell").val("paid");
}

function sellApartment() {
  var apartmentId = $("#sellApartmentId").val();
  var buyerName = $("#buyerName").val();
  var buyerPhone = $("#buyerPhone").val();
  var paymentStatus = $("#paymentStatusSell").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      apartment_id: apartmentId,
      buyer_name: buyerName,
      buyer_phone: buyerPhone,
      payment_status: paymentStatus,
      apartment_availability: "sold",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Apartment sold successfully");
        closeSellModal();
        fetchApartments();
      } else {
        alert("Error selling apartment: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error selling apartment. Status: " + status + ", Error: " + error);
    },
  });
}

function openRentModal(apartmentId) {
  $("#rentModal").show();
  $("#rentApartmentId").val(apartmentId);
}
function closeRentModal() {
  $("#rentModal").hide();
  // Clear input fields on modal close
  $("#tenantName").val("");
  $("#tenantPhone").val("");
  $("#paymentStatusRent").val("paid");
}

function rentApartment() {
  var apartmentId = $("#rentApartmentId").val();
  var tenantName = $("#tenantName").val();
  var tenantPhone = $("#tenantPhone").val();
  var paymentStatus = $("#paymentStatusRent").val();

  $.ajax({
    type: "POST",
    url: "api/sell_and_rent.php",
    data: {
      apartment_id: apartmentId,
      buyer_name: tenantName,
      buyer_phone: tenantPhone,
      payment_status: paymentStatus,
      apartment_availability: "rented",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Apartment rented successfully");
        closeRentModal();
        fetchApartments();
      } else {
        alert("Error renting apartment: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      alert("Error renting apartment. Status: " + status + ", Error: " + error);
    },
  });
}
