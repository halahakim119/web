<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employee') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Home</title>

</head>

<body>
    <div class="container">
        <h1>Welcome,
            <?php echo $_SESSION['username']; ?> (Employee)
        </h1>

        <!-- Logout button -->
        <form action="api/logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>

</html>