<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Apartments</title>

    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="styles.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="apartments.js"></script>
</head>

<body>
    <header>
        <button class="back-button" onclick="goBack()">Back</button>
        <span class="title">Manage Apartments</span>
    </header>

    <div class="container">
        <!-- Search bar -->
        <input type="text" id="searchQuery" placeholder="Search by location/seller/phone">
        <button onclick="searchApartments()">Search</button>
        <!-- Add Apartment button -->
        <button class="add-button" onclick="openAddApartmentModal()">Add Apartment</button>
        <!-- Table to display apartments -->
        <table border="1">
            <thead>
                <tr>
                    <th>Apartment ID</th>
                    <th>Location</th>
                    <th>Area</th>
                    <th>Width</th>
                    <th>Length</th>
                    <th>Availability</th>
                    <th>Price</th>
                    <th>Seller Name</th>
                    <th>Seller Phone</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody id="apartmentTableBody">
                <!-- Apartments will be displayed here dynamically using JavaScript -->
            </tbody>
        </table>


    </div>

    <!-- Add Apartment Modal (hidden by default) -->
    <div id="addApartmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddApartmentModal()">&times;</span>
            <h2>Add Apartment</h2>
            <label for="addLocation">Full Location:</label>
            <input type="text" id="addLocation" required>


            <div class="form-row">
                <div class="form-column">
                    <label for="addArea">Area:</label>
                    <input type="text" id="addArea" required>
                </div>
                <div class="form-column">
                    <label for="addWidth">Width:</label>
                    <input type="text" id="addWidth" required>
                </div>
                <div class="form-column">
                    <label for="addLength">Length:</label>
                    <input type="text" id="addLength" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <label for="addForSell">For Sell:</label>
                    <input type="checkbox" id="addForSell" onclick="uncheckRentCheckbox()">
                </div>
                <div class="form-column">
                    <label for="addForRent">For Rent:</label>
                    <input type="checkbox" id="addForRent" onclick="uncheckSellCheckbox()">
                </div>
            </div>

            <label for="addPrice">Price:</label>
            <input type="text" id="addPrice" required>

            <label for="addSellerName">Seller Name:</label>
            <input type="text" id="addSellerName" required>

            <label for="addSellerPhone">Seller Phone:</label>
            <input type="text" id="addSellerPhone" required>

            <button onclick="validateAndAddApartment()">Save</button>
            <button onclick="closeAddApartmentModal()">Cancel</button>
        </div>
    </div>

    <!-- Sell Apartment Modal -->
    <div id="sellModal" style="display: none;">
        <div class="modal-content">
            <input type="hidden" id="sellApartmentId">
            <span class="close" onclick="closeSellModal()">&times;</span>
            <h2>Sell Apartment</h2>
            <label for="buyerName">Buyer Name:</label>
            <input type="text" id="buyerName" required>

            <label for="buyerPhone">Buyer Phone:</label>
            <input type="text" id="buyerPhone" required>

            <label for="paymentStatusSell">Payment Status:</label>
            <select id="paymentStatusSell" required>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>

            <button onclick="validateAndSellApartment()">Sell</button>
            <button onclick="closeSellModal()">Cancel</button>
        </div>
    </div>

    <!-- Rent Apartment Modal -->
    <div id="rentModal" style="display: none;">
        <div class="modal-content">
            <input type="hidden" id="rentApartmentId">
            <span class="close" onclick="closeRentModal()">&times;</span>
            <h2>Rent Apartment</h2>
            <label for="tenantName">Tenant Name:</label>
            <input type="text" id="tenantName" required>

            <label for="tenantPhone">Tenant Phone:</label>
            <input type="text" id="tenantPhone" required>

            <label for="paymentStatusRent">Payment Status:</label>
            <select id="paymentStatusRent" required>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>

            <button onclick="validateAndRentApartment()">Rent</button>
            <button onclick="closeRentModal()">Cancel</button>
        </div>
    </div>

    <!-- Edit Apartment Modal -->
    <div id="editApartmentModal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditApartmentModal()">&times;</span>
            <h2>Edit Apartment</h2>
            <input type="hidden" id="editApartmentId">

            <label for="editLocation">Full Location:</label>
            <input type="text" id="editLocation" required>

            <div class="form-row">
                <div class="form-column">
                    <label for="editArea">Area:</label>
                    <input type="text" id="editArea" required>
                </div>
                <div class="form-column">
                    <label for="editWidth">Width:</label>
                    <input type="text" id="editWidth" required>
                </div>
                <div class="form-column">
                    <label for="editLength">Length:</label>
                    <input type="text" id="editLength" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <label for="editForSell">For Sell:</label>
                    <input type="checkbox" id="editForSell" onclick="uncheckEditRentCheckbox()">
                </div>
                <div class="form-column">
                    <label for="editForRent">For Rent:</label>
                    <input type="checkbox" id="editForRent" onclick="uncheckEditSellCheckbox()">
                </div>
            </div>

            <label for="editPrice">Price:</label>
            <input type="text" id="editPrice" required>

            <!-- Include Seller Name and Phone Fields -->
            <label for="editSellerName">Seller Name:</label>
            <input type="text" id="editSellerName" required>

            <label for="editSellerPhone">Seller Phone:</label>
            <input type="text" id="editSellerPhone" required>

            <button onclick="validateAndEditApartment()">Save</button>
            <button onclick="closeEditApartmentModal()">Cancel</button>
        </div>
    </div>

    <script>

        function validateAndAddApartment() {
            var addLocation = document.getElementById("addLocation").value;
            var addArea = document.getElementById("addArea").value;
            var addWidth = document.getElementById("addWidth").value;
            var addLength = document.getElementById("addLength").value;
            var addForSell = document.getElementById("addForSell").checked;
            var addForRent = document.getElementById("addForRent").checked;
            var addPrice = document.getElementById("addPrice").value;
            var addSellerName = document.getElementById("addSellerName").value;
            var addSellerPhone = document.getElementById("addSellerPhone").value;

            // Validate non-empty fields
            if (addLocation === "" || addArea === "" || addWidth === "" || addLength === "" || addPrice === "" || addSellerName === "" || addSellerPhone === "") {
                alert("All fields must be filled out");
                return;
            }

            // Validate correct types
            if (isNaN(parseFloat(addArea)) || isNaN(parseFloat(addWidth)) || isNaN(parseFloat(addLength)) || isNaN(parseFloat(addPrice))) {
                alert("Area, Width, Length, and Price must be valid numbers");
                return;
            }

            // Additional validation logic if needed...

            // Call your addApartment function
            addApartment();
        }

        function validateAndSellApartment() {
            var buyerName = document.getElementById("buyerName").value;
            var buyerPhone = document.getElementById("buyerPhone").value;
            var paymentStatusSell = document.getElementById("paymentStatusSell").value;

            // Validate non-empty fields
            if (buyerName === "" || buyerPhone === "") {
                alert("All fields must be filled out");
                return;
            }

            // Additional validation logic if needed...

            // Call your sellApartment function
            sellApartment();
        }

        function validateAndRentApartment() {
            var tenantName = document.getElementById("tenantName").value;
            var tenantPhone = document.getElementById("tenantPhone").value;
            var paymentStatusRent = document.getElementById("paymentStatusRent").value;

            // Validate non-empty fields
            if (tenantName === "" || tenantPhone === "") {
                alert("All fields must be filled out");
                return;
            }

            // Additional validation logic if needed...

            // Call your rentApartment function
            rentApartment();
        }

        function validateAndEditApartment() {
            var editLocation = document.getElementById("editLocation").value;
            var editArea = document.getElementById("editArea").value;
            var editWidth = document.getElementById("editWidth").value;
            var editLength = document.getElementById("editLength").value;
            var editForSell = document.getElementById("editForSell").checked;
            var editForRent = document.getElementById("editForRent").checked;
            var editPrice = document.getElementById("editPrice").value;
            var editSellerName = document.getElementById("editSellerName").value;
            var editSellerPhone = document.getElementById("editSellerPhone").value;

            // Validate non-empty fields
            if (editLocation === "" || editArea === "" || editWidth === "" || editLength === "" || editPrice === "" || editSellerName === "" || editSellerPhone === "") {
                alert("All fields must be filled out");
                return;
            }

            // Validate correct types
            if (isNaN(parseFloat(editArea)) || isNaN(parseFloat(editWidth)) || isNaN(parseFloat(editLength)) || isNaN(parseFloat(editPrice))) {
                alert("Area, Width, Length, and Price must be valid numbers");
                return;
            }

            // Additional validation logic if needed...

            // Call your editApartment function
            editApartment();
        }

        function goBack() {
            window.history.back();
        }

        function uncheckRentCheckbox() {
            document.getElementById("addForRent").checked = false;
        }

        function uncheckSellCheckbox() {
            document.getElementById("addForSell").checked = false;
        }

        function uncheckEditRentCheckbox() {
            document.getElementById("editForRent").checked = false;
        }

        function uncheckEditSellCheckbox() {
            document.getElementById("editForSell").checked = false;
        }
    </script>
</body>

</html>