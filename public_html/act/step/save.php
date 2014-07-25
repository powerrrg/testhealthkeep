<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_GET["t"]) || !isset($_GET["s"])){
	go404();
}

$step=(int)$_GET["s"];

if($step==0){
	go404();
}

if($_GET["t"]=="u"){
	
	if($step==1){
		require_once(ENGINE_PATH."html/step/save_user_1.php");
	}else if($step==2 || $step==3 || $step==4 || $step==5){
		require_once(ENGINE_PATH."html/step/save_topic.php");
	}else if($step==6){
		require_once(ENGINE_PATH."html/step/save_user_doctors.php");
	}else{
		go404();
	}
	
}else if($_GET["t"]=="d"){

	if($step==1){
		require_once(ENGINE_PATH."html/step/save_doctor_1.php");
	}else if($step==2 || $step==3 || $step==4 || $step==5){
		require_once(ENGINE_PATH."html/step/save_topic_doctor.php");
	}else{
		go404();
	}
	
}else{
	go404();
}

if(!isset($profileClass)){
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
}

$profileClass->saveStep($step);

$step++;

if($step>6){
	header("Location:".WEB_URL.USER_NAME);
}else{
	header("Location:".WEB_URL."step/".$step);
}