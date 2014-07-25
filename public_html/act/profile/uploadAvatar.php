<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(isset($_FILES["avatarFile"])){
	
	if($_FILES["avatarFile"]["error"]!=0 || $_FILES["avatarFile"]["size"]>2097152){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$res=$profileClass->changeAvatar("avatarFile");

	if($res){
		$goto=WEB_URL.USER_NAME;
	}else{
		$goto=WEB_URL.USER_NAME."#error";
	}
	
	header("Location:".$goto);
	
}else{
	go404();
}