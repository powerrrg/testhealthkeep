<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["name"])){
	go404();
}

$name=$_POST["name"];

if(strlen($name)<6){
	go404();
}


require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->updateName($name);


header("Location:".WEB_URL."account/details#ok");