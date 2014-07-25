<?php

if(!isset($_POST["topic"])){
	go404();
}

$topic=$_POST["topic"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	

		if($res>0){
		
			$resTopic=$profileClass->getById($res);

			if($resTopic["result"] && $resTopic[0]["type_profile"]=="2"){

				$timelineClass->addDoctorVisit($res,$today);
				
			}
			
		}
	}
	
}