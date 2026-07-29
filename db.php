<?php

function getConnection()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "flowers";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
