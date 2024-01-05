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
    <title>Manage Apartments</title>
    <!-- Include any necessary styles or scripts -->
</head>

<body>
    <div class="container">
        <h1>Manage Apartments</h1>
        <!-- Add content specific to managing apartments -->
    </div>
</body>

</html>