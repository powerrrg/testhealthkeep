<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["topic"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"])){
	
	$topic=(int)$_POST["topic"];
	if($topic==0){
		go404();
	}
	$month=(int)$_POST["month"];
	if($month==0){
		go404();
	}
	$day=(int)$_POST["day"];
	if($day==0){
		go404();
	}
	$year=(int)$_POST["year"];
	$currentYear=date('Y');
	$allowedYear=$currentYear+5;
	if($year==0 || $year<1920 || $year>$allowedYear){
		go404();
	}
	
	if(!checkdate($month, $day, $year)){
		go404();
	}
	$date_tm=$year."-".$month."-".$day;
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$res=$profileClass->getById($topic);
	if(!$res["result"]){
		go404();
	}
	
	if($res[0]["type_profile"]!=2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$res=$timelineClass->addDoctorVisit($topic,$date_tm);

	if($res){
		$goto=WEB_URL."timeline";
	}else{
		$goto=WEB_URL."timeline#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}