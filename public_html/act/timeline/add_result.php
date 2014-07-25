<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["name"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"]) && isset($_FILES["file"])){
	
	if($_FILES["file"]["error"]!=0 || $_FILES["file"]["size"]>2097152){
		go404();
	}
	
	$name=$_POST["name"];
	
	if(strlen($name)<3 || strlen($name)>250){
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
	if($year==0 || $year<1920 || $year>$currentYear){
		go404();
	}
	
	if(!checkdate($month, $day, $year)){
		go404();
	}
	$date_tm=$year."-".$month."-".$day;
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$res=$timelineClass->addTestResult($name,$date_tm,'file');

	if($res){
		$goto=WEB_URL."timeline";
	}else{
		$goto=WEB_URL."timeline#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}