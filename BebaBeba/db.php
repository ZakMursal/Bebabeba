<?php
// Establishing connection to the MySQL database
$con = mysqli_connect("localhost", "root", "", "bebabeba");

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

