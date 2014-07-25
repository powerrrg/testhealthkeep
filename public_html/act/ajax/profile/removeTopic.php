<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["id"])){
	$id=(int)$_POST["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$resTopic=$topicClass->getById($id);
			
	if($resTopic["result"]){
	
		$res=$topicClass->unfollow($id);
		
		if(!$res){
			echo "error";
		}else{
			echo "ok";
		}
		
	}else{
		echo "error";
	}
	
}else{
	go404();
}