<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["weight"]) && (isset($_POST["feets"]) || isset($_POST["inches"]))){
	
	$weight=$_POST["weight"];

	if(!is_numeric($weight) || $weight<7 || $weight>1500){
		$weight=0;
	}
	
	$feets=(int)$_POST["feets"];
	
	if($feets<1 || $feets>10){
		$feets=0;
	}
	
	$inches=(int)$_POST["inches"];
	
	if(!is_numeric($inches) || $inches<1 || $inches>100){
		$inches=0;
	}

	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$profileClass->updateWeightHeight($weight,$feets,$inches);
	
	header("Location:".WEB_URL.USER_NAME);
	
}else{
	go404();
}