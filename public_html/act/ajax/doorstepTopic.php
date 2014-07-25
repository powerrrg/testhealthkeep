<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["topic"])){
	
	$topic=(int)$_POST["topic"];
	if(strlen($topic)>0){
		echo "ok";
	}
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	$res=$topicClass->follow($topic);
	
	if(!$res){
		echo "error";
	}else{
		echo "ok";
	}

}else{
	go404();
}