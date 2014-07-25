<?php
require_once('../../../engine/starter/config.php');

if(USER_ID!=0){
	header("Location:".WEB_URL);
	exit;
}

if(isset($_POST["email"]) && isset($_POST["hpot"]) && isset($_POST["token"]) && isset($_SESSION["token"])){
	if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
		header("Location:".WEB_URL."forgot.php?error");
		exit;
	}else{
		
		$email=$_POST["email"];
		$_SESSION["token"]="";
				
		$res=$userClass->getByEmail($email);
		
		if($res["result"]){
			
			$res=$userClass->requestPassword($res[0]["id_user"]);
			if($res=="ok"){
				header("Location:".WEB_URL."forgot.php?ok");
			}else if($res=="time"){
				header("Location:".WEB_URL."forgot.php?time");
			}else{
				header("Location:".WEB_URL."forgot.php?error");
			}
		}else{
			header("Location:".WEB_URL."forgot.php?email");
		}

	}
}else{
	go404();
}