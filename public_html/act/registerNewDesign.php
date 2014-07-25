<?php
require_once('../../engine/starter/config.php');


if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["gender"]) && isset($_POST["hpot"]) && isset($_POST["token"]) && isset($_SESSION["token"])){
	if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
		go404();
	}else{
		$username=trim($_POST["username"]);
		$email=trim($_POST["email"]);
		$password=$_POST["password"];
		$gender=$_POST["gender"];
		$_SESSION["token"]="";
				
		$res=$userClass->addNew($username,$email,$password,$gender);
		
		if(!$res["result"]){
			if(isset($res["error"])){
				if(isset($res["emailDup"])){
					$_SESSION["emailDup"]=$email;
					header("Location:".WEB_URL."login.php");
					exit;
				}else{
					echo $res["error"];
				}
			}else{
				echo "Something really strange happened. Please try again or contact us!";
			}
		}else{
			header("Location:".WEB_URL."start.php");
			exit;
		}
		
	}
}else{
	go404();
}
