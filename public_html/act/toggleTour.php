<?php

require_once('../../engine/starter/config.php');

onlyLogged();

if(!isset($_GET["set"])){
	go404();
}

$v=(int)$_GET["set"];

if($v!=1){
	$v=0;
}



$res=$userClass->toggleTour($v);


header("Location:".WEB_URL."feed");