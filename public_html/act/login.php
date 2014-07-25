<?php
require_once('../../engine/starter/config.php');

if(isset($_GET["go"])){
	$goto=$_GET["go"];
	$gotoParam="&go=".$_GET["go"];
}else{
	$goto="feed";
	$gotoParam="";
}

if(USER_ID!=0){
	if(isset($_GET["logout"])){
		$userClass->doLogout();
	}else{
		header("Location:".WEB_URL.$goto);
		exit;
	}
}else if(isset($_GET["logout"])){
	//in case the session has expired still can logout
	$userClass->doLogout();
}

if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["hpot"]) && isset($_POST["token"]) && isset($_SESSION["token"])){
	if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
		header("Location:".WEB_URL."login.php?refresh".$gotoParam);
		exit;
	}else{
		
		$email=$_POST["email"];
		$password=$_POST["password"];
		$_SESSION["token"]="";
				
		$res=$userClass->doLogin($email,$password);
		
		if($res["result"]){
		
			//renew mixpanel info
			$_SESSION["mx_name_tag"]=0;
			
			require_once(ENGINE_PATH.'class/profile.class.php');
			$profileClass=new Profile();
			$resProfile=$profileClass->getById($_SESSION["user_id"]);
			if($resProfile["result"]){
				
				/*if($gotoParam!="" && $goto!=""){
					header("Location:".WEB_URL.$goto);
				}else if(preg_match("/99/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL."feed");
				}else if(preg_match("/5/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL.$goto);
				}else if(preg_match("/4/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL."step/5");
				}else if(preg_match("/3/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL."step/4");
				}else if(preg_match("/2/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL."step/3");
				}else if(preg_match("/1/", $resProfile[0]["steps_profile"])){
					header("Location:".WEB_URL."step/2");
				}else{
					if($resProfile[0]["type_profile"]==1){
						header("Location:".WEB_URL."onestep");
						//header("Location:".WEB_URL."privacy"); - it is not checking by default all go onestep
					}else if($resProfile[0]["type_profile"]==2){
						header("Location:".WEB_URL."step/1");
					}else{*/
					if($gotoParam!="" && $goto!=""){
						header("Location:".WEB_URL.$goto);
					}else{
						header("Location:".WEB_URL."feed");
					}
					//}
					
				//}
			}else{
				header("Location:".WEB_URL.$goto);
			}
		}else{
			if(isset($res["error"])){
			
				if($res["error"]=="email"){
					header("Location:".WEB_URL."login.php?email".$gotoParam);
					exit;
				}else if($res["error"]=="password"){
					header("Location:".WEB_URL."login.php?password".$gotoParam);
					exit;
				}else{
					header("Location:".WEB_URL."login.php?refresh".$gotoParam);
					exit;
				}
				
			}else{
				header("Location:".WEB_URL."login.php?refresh".$gotoParam);
				exit;
			}
		}

	}
}else{
	header("Location:".WEB_URL."login.php?refresh".$gotoParam);
	exit;
}