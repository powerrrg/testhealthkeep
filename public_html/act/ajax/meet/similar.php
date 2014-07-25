<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["p"])){
	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	require_once(ENGINE_PATH.'class/location.class.php');
	$locationClass=new Location();
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$res=$profileClass->findSimilar($pageNum);
	
	require_once(ENGINE_PATH."render/others/meet_list.php");
	
}else{
	go404();
}