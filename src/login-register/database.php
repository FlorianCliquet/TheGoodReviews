<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "TheGoodReviews";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$mysqli = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

/*if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}*/
return $mysqli;

?>
