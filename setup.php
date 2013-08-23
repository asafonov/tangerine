<?php

require_once('autoload.php');

$user = new user();
$user->create(array('login'=>'demo', 'password'=>'demo', 'email'=>'demo@mailforspam.com'));
?>
