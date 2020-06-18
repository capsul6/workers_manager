<?php
//set main settings
class DB_config {

private $user;
private $password;
private $dsn;
private $opt;

const DSN_MYSQL = "mysql:host=localhost;dbname=test;charset=utf8";
const OPT = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];


public function __construct($user, $password)
{
    $this->user = $user;
    $this->password = $password;
    $this->dsn = self::DSN_MYSQL;
    $this->opt = self::OPT;
}

public function getDBConnection() {
    return new PDO($this->dsn, $this->user, $this->password, $this->opt);
}

}


?>