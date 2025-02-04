<?php
$type = "mysql";
$servername = "mysql";
$username = "root";
$password = "secret123";
$database = "webshop";

try {
    $conn = new PDO("$type:host=$servername;dbname=$database;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>