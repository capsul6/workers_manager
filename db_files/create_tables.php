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
password VARCHAR(70) NOT NULL,
email VARCHAR(30)
);

CREATE TABLE workers (
worker_id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
surname VARCHAR(30) NOT NULL,
image MEDIUMBLOB,
tellNumber INT(50),
dateOfBirth DATE,
position VARCHAR(50) NOT NULL,
rank VARCHAR(50) NOT NULL,
user_id INT(20),
outside_id INT(20),
CONSTRAINT 'user_id' FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE outside_records(
outside_id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
date_come DATE,
date_return DATE,
worker_id INT(20),
CONSTRAINT  'worker_id' FOREIGN KEY (worker_id) REFERENCES workers(worker_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";




if($connection->query($query)) {
    echo "database is successfully created";
}

?>

