<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["weight"])){
	
	if(!is_numeric($_POST["weight"])){
		go404();
	}
	$weight=(float)$_POST["weight"];
	if($weight==0 || $weight<7 || $weight>1500){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$nowtime=time();
	$nowtime=date("Y-m-d",$nowtime);
	
	$res=$timelineClass->addMeasurement("weight",$weight,$nowtime);

	if($res){
		echo "ok";
	}else{
		echo "error";
	}
	
}else{
	go404();
}