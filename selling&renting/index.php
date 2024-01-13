<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage RentedAndSolds</title>

    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="styles.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="rentedAndSolds.js"></script>
</head>

<body>
    <header>
        <button class="back-button" onclick="goBack()">Back</button>
        <span class="title">Manage RentedAndSolds</span>
    </header>

    <div class="container">
        <!-- Search bar -->
        <input type="text" id="searchQuery" placeholder="Search by buyer name/phone">
        <button onclick="searchRentedAndSolds()">Search</button>

        <!-- Table to display rentedAndSolds -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>property id</th>
                    <th>property type</th>
                    <th>buyer name</th>
                    <th>buyer phone number</th>
                    <th>payment status</th>
                    <th>availability</th>

                    <th>actions</th>
                </tr>
            </thead>
            <tbody id="rentedAndSoldTableBody">
                <!-- RentedAndSolds will be displayed here dynamically using JavaScript -->
            </tbody>
        </table>


    </div>



    <script>




        function goBack() {
            window.history.back();
        }

    </script>
</body>

</html>