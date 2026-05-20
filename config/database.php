<?php
$host = "localhost";
$user = "root";       // default WAMP username
$password = "";       // default WAMP password (empty)
$database = "movie_booking";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>