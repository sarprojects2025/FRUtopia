<?php

date_default_timezone_set("Asia/Kolkata");

$username = "fruuser";
$db = "frutopia";
$password = "frupass";
$host = "localhost";
//$host = '89.116.138.57';

$con = new mysqli($host, $username, $password, $db);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    //echo "Connected succesfull";
}


?>
