<?php
$host = "localhost";
$username = "oranges";
$password = "orange1234";
$dbname = "orangestore";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}
