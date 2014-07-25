<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["profile"])){
	
	$profile=(int)$_POST["profile"];
	if(strlen($profile)>0){
		echo "ok";
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	$res=$profileClass->follow($profile);
	
	if(!$res){
		echo "error";
	}else{
		echo "ok";
	}

}else{
	go404();
}