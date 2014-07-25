<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["experience"])){
	
	$experience=$_POST["experience"];
	if(strlen($experience)<5){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/post.class.php');
	$postClass=new Post();
	$res=$postClass->addNewNoTopic($experience);
	
	if(!$res){
		echo "error";
	}else{
		echo "ok";
	}

}else{
	go404();
}