<?php
$host = "localhost";
$user = "root";
$pass = "Moussamj9$";
$dbname = "chat_app";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
