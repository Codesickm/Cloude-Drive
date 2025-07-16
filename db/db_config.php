<?php
$host = "localhost";
$user = "root"; // your XAMPP/WAMP username
$pass = "";     // password (empty for XAMPP)
$db   = "mohitdrive";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
