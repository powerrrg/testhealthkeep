<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["id"])){
	
	$id=(int)$_GET["id"];
	if($id==0){
		go404();
	}

	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$res=$timelineClass->getById($id);
	
	if(!$res["result"]){
		go404();
	}
	
	if(USER_ID!=$res[0]["id_profile_tm"]){
		go404();
	}
	
	$res=$timelineClass->notCurrently($id);

	if($res){
		$goto=WEB_URL."timeline";
	}else{
		$goto=WEB_URL."timeline#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}