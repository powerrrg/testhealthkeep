<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["weight"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"])){
	
	if(!is_numeric($_POST["weight"])){
		go404();
	}
	$weight=(float)$_POST["weight"];
	if($weight==0 || $weight<7 || $weight>1500){
		go404();
	}
	
	$date_tm=$year."-".$month."-".$day;
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$res=$timelineClass->addMeasurement("weight",$weight,$date_tm);

	if($res){
		header("Location:".WEB_URL."timeline");	
	}else{
		header("Location:".WEB_URL."timeline#error");
	}
	
	
}else{
	go404();
}