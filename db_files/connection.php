<?php
//set main settings
class DBconfig {

public static $user = "root";
public static $password = "";

public static $dsn = "mysql:host=localhost;dbname=test;charset=utf8";
public static $opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

public static function getDBConnection() : PDO {
    $connection = new PDO(self::$dsn, self::$user, self::$password, self::$opt);
    return $connection;
    }
}
?>