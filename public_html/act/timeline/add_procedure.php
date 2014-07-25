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
	if($year==0 || $year<1920 || $year>$currentYear){
		go404();
	}
	
	if(!checkdate($month, $day, $year)){
		go404();
	}
	$date_tm=$year."-".$month."-".$day;
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$res=$topicClass->getById($topic);
	if(!$res["result"]){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$topicAlready=$timelineClass->topicAlreadyInTimeline($topic);
	
	$res=$timelineClass->add("pro",$topic,0,$date_tm,"0000-00-00");

	if($res){
		if($topicAlready["result"]){
			header("Location:".WEB_URL."timeline");	
		}else{
			header("Location:".WEB_URL."timeline/new/".$topic);	
		}
	}else{
		header("Location:".WEB_URL."timeline#error");
	}
	
}else{
	go404();
}