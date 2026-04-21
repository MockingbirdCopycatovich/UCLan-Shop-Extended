<?php
$host = "localhost";
$user = "vvasilev1";
$pass = "xfD2JdhyNj";
$db = "vvasilev1";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>