<!-- index.php -->

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <!-- Include any necessary styles or scripts -->
    <link rel="stylesheet" href="styles/adminhome.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <header>
        <div class="user-info">

            <span class="username"> Welcome
                <?php echo $_SESSION['username']; ?>
            </span>
        </div>
        <form action="api/logout.php" method="post">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </header>

    <div class="container">


        <!-- Manage Houses button -->
        <button onclick="redirectTo('house/index.php')">Manage Houses</button>

        <!-- Manage Apartments button -->
        <button onclick="redirectTo('apartment/index.php')">Manage Apartments</button>

        <!-- Manage Lands button -->
        <button onclick="redirectTo('land/index.php')">Manage Lands</button>

        <!-- Manage Sellings & Renting button -->
        <button onclick="redirectTo('selling&renting/index.php')">Manage Sellings & Renting</button>

        <!-- Manage Users button -->
        <button onclick="redirectTo('users/index.php')">Manage Users</button>
    </div>

    <script>
        function redirectTo(url) {
            window.location.href = url;
        }
    </script>
</body>

</html>