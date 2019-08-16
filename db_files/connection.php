<?php
//set main settings
$host = "localhost";
$user = "root";
$password = "";
$database = "test";


$dsn = "mysql:host={$host};dbname={$database};charset=utf8";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
?>