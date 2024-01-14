<?php

$servername = "localhost";
$username = "myuhqywbnx";
$password = "wDfxXF4ure";
$dbname = "myuhqywbnx";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>