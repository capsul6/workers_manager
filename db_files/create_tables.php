<?php

require 'connection.php';

$connection = new mysqli($host, $user, $password, $database);

if(!$connection) {
    die("Ошибка при подключении к БД" . $connection->error);
}


$query =
    "CREATE TABLE users(
id INT(6) AUTO_INCREMENT PRIMARY KEY,
login VARCHAR(30) NOT NULL,
password VARCHAR(30) NOT NULL,
email VARCHAR(30)
)";

$connection->query($query);


?>

