<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_GET["id"]) && (isset($_GET["yes"]) || isset($_GET["no"]))){
	
	$id=(int)$_GET["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resProfile=$profileClass->getById($id);
	
	if(!$resProfile["result"]){
		go404();
	}
	
	if(isset($_GET["yes"])){
		$res=$profileClass->follow($id);
	}else{
		$res=$profileClass->unfollow($id);
	}
	
	

	if($res){
		$goto=WEB_URL.$resProfile[0]["username_profile"];
	}else{
		$goto=WEB_URL.$resProfile[0]["username_profile"]."#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}