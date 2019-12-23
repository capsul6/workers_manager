<?php
session_start();
$_SESSION = array();
session_destroy();
setrawcookie('login', null, time());
header('Location: index.php');
?>