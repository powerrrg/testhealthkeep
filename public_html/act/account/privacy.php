<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["username"]) || !isset($_POST["accepted"])){
	go404();
}

$username=trim($_POST["username"]);

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

if($_POST["accepted"]!=1){
	go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}

if($username!=$resProfile[0]["username_profile"]){
	$res=$profileClass->getByUsername($username);
	if($res["result"]){
		go404();
	}
	$res=$profileClass->updateUsername($username);
}


if($theimage<=12){
	if($resProfile[0]["gender_profile"]=="f"){
		$gender="woman";
	}else{
		$gender="man";
	}
	$res=$profileClass->copyAvatar($gender.$theimage.".jpg");
}else{
	$res=$profileClass->changeAvatar("avatarFile");
}

header("Location:".WEB_URL."step/1");
