<?php
session_start();
$_SESSION = array();
session_destroy();
setrawcookie('login', null, time());
setrawcookie('name', null, time());
setrawcookie('surname', null, time());
header('Location: index.php');
?>