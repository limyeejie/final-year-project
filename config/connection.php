<?php
// Connect to localhost phpmyadmin
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventmanagementsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: ". $conn->connect_error);
}


?>