<?php

if(!isset($_POST["topic"])){
	go404();
}

$topic=$_POST["topic"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	
		if($res>0){
		
			$resTopic=$topicClass->getById($res);
			
			if($resTopic["result"]){
			
				if($resTopic[0]["type_topic"]=="d"){
					$res=$timelineClass->add("dis",$res,1,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="m"){
					$res=$timelineClass->add("med",$res,1,$today,"0000-00-00",1,0,0);
				}else if($resTopic[0]["type_topic"]=="p"){
					$res=$timelineClass->add("pro",$res,0,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="s"){
					$res=$timelineClass->addSymptoms(array($res),1,$today);
				}
				
			}
			
		}
	}
	
}