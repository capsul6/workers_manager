<?php

require_once("DB_config.php");


$DB_object = new DB_config("root", "");
$connection = $DB_object->getDBConnection();

if(!$connection) {
    die("Ошибка при подключении к БД" . $connection->errorCode());
} else {
    $connection->exec($query);
    echo "TABLES was successfully created";
}


$query =
"CREATE TABLE users(
id INT(6) AUTO_INCREMENT PRIMARY KEY,
login VARCHAR(30) NOT NULL,
password VARCHAR(70) NOT NULL,
email VARCHAR(50)
);

CREATE TABLE workers (
worker_id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
surname VARCHAR(30) NOT NULL,
image MEDIUMBLOB,
image_file_name varchar(100),
tellNumber INT(50),
dateOfBirth DATE,
position VARCHAR(50) NOT NULL,
rank VARCHAR(50) NOT NULL,
permission_type VARCHAR(255) NOT NULL DEFAULT NULL,
user_id INT(20),
outside_id INT(20),
CONSTRAINT 'user_id' FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE outside_records(
outside_id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
date_come DATE,
date_return DATE,
outside_type VARCHAR(255),
worker_id INT(20),
CONSTRAINT 'worker_id' FOREIGN KEY (worker_id) REFERENCES workers(worker_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE articles(
article_id INT(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
file_location VARCHAR(50) DEFAULT NULL,
posted_date DATE,
description VARCHAR(255),
list_of_acquainted INT(30),
CONSTRAINT 'list_of_acquainted' FOREIGN KEY (list_of_acquainted) REFERENCES list_of_acquainted(list_of_acquainted_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE list_of_acquainted(
list_of_acquainted_id INT(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
article_id INT(30) NOT NULL,
user_name VARCHAR(255) NOT NULL,
date_of_acquainted DATE,
CONSTRAINT 'article_id' FOREIGN KEY (article_id) REFERENCES articles(list_of_acquainted) ON UPDATE CASCADE ON DELETE CASCADE
);
";
?>

