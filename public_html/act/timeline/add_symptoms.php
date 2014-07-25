<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["topic"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["year"]) && isset($_POST["takingit"])){

	$topic=$_POST["topic"];
	
	if($topic==""){
		go404();
	}
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$cleanIds=array();
	
	foreach($pieces as $key=>$value){
		$res=(int)$value;	
		if($res>0){
		
			$resTopic=$topicClass->getById($res);
			
			if($resTopic["result"]){
			
				$cleanIds[]=$res;
				
			}
			
		}
	}
	
	if(count($cleanIds)==0){
		go404();
	}

	$currently=0;
	if($_POST["takingit"]==1){
		$currently=1;
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
	
	$res=$timelineClass->addSymptoms($cleanIds,$currently,$date_tm);

	if($res){
		$goto=WEB_URL."timeline";
	}else{
		$goto=WEB_URL."timeline#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}