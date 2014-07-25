<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["id"])){
	$id=(int)$_POST["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resProfile=$profileClass->getById($id);
			
	if($resProfile["result"] && $resProfile[0]["type_profile"]==2){
	
		$res=$profileClass->unfollow($id);
		
		if($res){
			echo "ok";
		}else{
			//echo "error 1";
			echo "error";
		}
		
	}else{
		echo "error";
	}
	
}else{
	go404();
}