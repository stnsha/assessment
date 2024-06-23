<?php
/*
Create the Database connection here:
*/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assessment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
