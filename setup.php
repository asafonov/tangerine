<?php

require_once('autoload.php');

$user = new user();
$user->create(array('login'=>'demo', 'password'=>'demo', 'email'=>'demo@mailforspam.com'));

$page = new page();
$page->url = '/';
$page->blocks = array();
$page->blocks['user_login'] = array('type'=>'user->login', 'data'=>'');
$page->blocks['content'] = array('type'=>'text', 'data'=>'<h1>Main page</h1><p>This is the main page');
$page->save();
?>
