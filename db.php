<?php

$server = "localhost";
$database = "test_db";
$db_user = "root";
$db_pass = "";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (PDOException $error) {
    die("Unable to connect to the database. Please try again later.");
}
?>
