<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["bio"])){
	go404();
}

$bio=nl2br(substr($_POST["bio"], 0,450));

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->updateBio($bio);

if($res){
	echo "ok";
}else{
	echo "error";
}