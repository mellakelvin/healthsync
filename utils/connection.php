<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "database";

$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
