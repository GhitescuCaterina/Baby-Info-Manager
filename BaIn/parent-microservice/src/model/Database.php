<?php

$host = "localhost:3307";
$dbname = "ParentMicroservice";
$username = "root";
$password = "";

$mysqli =
    new mysqli(
        $host,
        $username,
        $password,
        $dbname
    );

if ($mysqli->connect_errno) {
    throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
}

return $mysqli;