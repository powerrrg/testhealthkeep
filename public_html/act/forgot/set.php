<?php
require_once('../../../engine/starter/config.php');

if(USER_ID!=0){
	header("Location:".WEB_URL);
	exit;
}

if(isset($_GET["url"]) && isset($_POST["password"]) && isset($_POST["hpot"]) && isset($_POST["token"]) && isset($_SESSION["token"])){
	if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
		header("Location:".WEB_URL."pw.php?errortoken");
		exit;
	}else{
		
		$password=$_POST["password"];
		$_SESSION["token"]="";
		$url=urlencode($_GET["url"]);
		
		require_once(ENGINE_PATH.'class/profile.class.php');
		$profileClass=new Profile();
		$resProfile=$profileClass->getByUsername($url);
		
		if(!$resProfile["result"]){
			header("Location:".WEB_URL."pw.php?error");
			exit;
		}
				
		$res=$userClass->saveNewPassword($resProfile[0]["id_profile"],$password);
		
		if($res){
			
			header("Location:".WEB_URL."pw.php?ok");
			
		}else{
			header("Location:".WEB_URL."pw.php?errorsave");
		}

	}
}else{
	go404();
}