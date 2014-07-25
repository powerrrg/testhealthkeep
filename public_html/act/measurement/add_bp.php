<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["bpt"]) && isset($_POST["bpb"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"])){
	
	if(!is_numeric($_POST["bpt"])){
		go404();
	}
	$bpt=(int)$_POST["bpt"];
	if($bpt<0 || $bpt>300){
		go404();
	}
	if(!is_numeric($_POST["bpb"])){
		go404();
	}
	$bpb=(int)$_POST["bpb"];
	if($bpb<0 || $bpb>300){
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
	
	$res=$timelineClass->addMeasurement2Values("bp",$bpt,$bpb,$date_tm);

	if($res){
		header("Location:".WEB_URL."timeline");	
	}else{
		header("Location:".WEB_URL."timeline#error");
	}
	
	
}else{
	go404();
}