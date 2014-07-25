<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["topic"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"]) && isset($_POST["takingit"]) && isset($_POST["frequency"]) && isset($_POST["unit"]) && isset($_POST["freq"])){
	
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
	
	$currently=0;
	if($_POST["takingit"]==1){
		$currently=1;
	}
	
	$frequency=$_POST["frequency"];
	if(!is_numeric($frequency)){
		$frequency=1;
	}
	
	$realfreq=(int)$_POST["freq"];
	$unit=(int)$_POST["unit"];
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$res=$topicClass->getById($topic);
	if(!$res["result"]){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$topicAlready=$timelineClass->topicAlreadyInTimeline($topic);
	
	$res=$timelineClass->add("med",$topic,$currently,$date_tm,"0000-00-00",$frequency,$realfreq,$unit);

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