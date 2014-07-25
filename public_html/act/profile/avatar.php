<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["theimage"]) && (int)$_POST["theimage"]<=12){
	$theimage=(int)$_POST["theimage"];
	if($theimage==0){
		go404();
	}
}else{
	$theimage=99;
}

if($theimage==99){
	if(!isset($_FILES["avatarFile"])){
		go404();
	}else if($_FILES["avatarFile"]["error"]!=0 || $_FILES["avatarFile"]["size"]>2097152){
		go404();
	}
}


require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}

if($theimage<=12){
	if($resProfile[0]["gender_profile"]=="f"){
		$curGender="woman";
	}else{
		$curGender="man";
	}
	$res=$profileClass->copyAvatar($curGender.$theimage.".jpg");
}else{
	$res=$profileClass->changeAvatar("avatarFile");
}

header("Location:".WEB_URL.$resProfile[0]["username_profile"]);	